<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class MobileApiController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $token = $user->createToken('android-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 201);
    }

    /**
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales invalidas.'],
            ]);
        }

        $token = $user->createToken('android-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Sesion cerrada correctamente.',
        ]);
    }

    public function productos(): JsonResponse
    {
        $productos = Producto::query()
            ->with('categoria:id,nombre')
            ->where('activo', true)
            ->orderBy('id', 'desc')
            ->get()
            ->map(function (Producto $producto) {
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'descripcion' => $producto->descripcion,
                    'precio' => (float) $producto->precio,
                    'categoria' => $producto->categoria?->nombre ?? 'General',
                ];
            });

        return response()->json($productos);
    }

    public function pedidos(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', 'string', 'max:30'],
            'desde' => ['nullable', 'date_format:Y-m-d'],
            'hasta' => ['nullable', 'date_format:Y-m-d'],
            'sort' => ['nullable', 'in:newest,oldest,total_desc,total_asc'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $query = Pedido::query()->where('user_id', $user?->id);

        if (!empty($validated['q'])) {
            $needle = trim($validated['q']);
            $query->where(function ($q) use ($needle) {
                $q->where('numero_pedido', 'like', "%{$needle}%")
                    ->orWhere('id', 'like', "%{$needle}%")
                    ->orWhereDate('creado_en', $needle);
            });
        }

        if (!empty($validated['estado']) && $validated['estado'] !== 'todos') {
            $query->where('estado', $validated['estado']);
        }

        if (!empty($validated['desde'])) {
            $query->whereDate('creado_en', '>=', $validated['desde']);
        }

        if (!empty($validated['hasta'])) {
            $query->whereDate('creado_en', '<=', $validated['hasta']);
        }

        $sort = $validated['sort'] ?? 'newest';
        switch ($sort) {
            case 'oldest':
                $query->orderBy('creado_en');
                break;
            case 'total_desc':
                $query->orderByDesc('total')->orderByDesc('creado_en');
                break;
            case 'total_asc':
                $query->orderBy('total')->orderByDesc('creado_en');
                break;
            case 'newest':
            default:
                $query->orderByDesc('creado_en');
                break;
        }

        $perPage = (int) ($validated['per_page'] ?? 10);
        $page = (int) ($validated['page'] ?? 1);
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        $pedidos = collect($paginator->items())->map(function (Pedido $pedido) {
            return [
                'id' => $pedido->id,
                'estado' => $pedido->estado,
                'fecha' => optional($pedido->creado_en)->format('Y-m-d H:i:s'),
                'total' => (float) $pedido->total,
                'items' => $pedido->items,
            ];
        });

        return response()->json([
            'data' => $pedidos,
            'page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'has_more' => $paginator->hasMorePages(),
        ]);
    }

    public function crearPedido(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'clienteNombre' => ['required', 'string', 'max:150'],
            'telefono' => ['required', 'string', 'max:25'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'metodoPago' => ['nullable', 'string', 'max:100'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.producto_id' => ['required', 'integer'],
            'items.*.nombre' => ['required', 'string'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
            'items.*.precio_unitario' => ['required', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
        ]);

        $stockCheck = $this->checkStock($validated['items']);
        if (!$stockCheck['ok']) {
            return response()->json([
                'message' => 'Stock insuficiente para completar el pedido.',
                'items' => $stockCheck['items'],
            ], 422);
        }

        $pedido = DB::transaction(function () use ($request, $validated) {
            $stockRows = Producto::query()
                ->whereIn('id', collect($validated['items'])->pluck('producto_id')->all())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($validated['items'] as $item) {
                /** @var Producto|null $producto */
                $producto = $stockRows->get((int) $item['producto_id']);
                if (!$producto) {
                    throw ValidationException::withMessages([
                        'items' => ['Uno de los productos ya no existe.'],
                    ]);
                }

                $cantidad = (int) $item['cantidad'];
                if ($producto->stock < $cantidad) {
                    throw ValidationException::withMessages([
                        'items' => ["Stock insuficiente para {$producto->nombre}."],
                    ]);
                }

                $producto->stock = max(0, (int) $producto->stock - $cantidad);
                $producto->save();
            }

            return Pedido::create([
                'user_id' => $request->user()?->id,
                'numero_pedido' => generateOrderNumber(),
                'nombre_cliente' => $validated['clienteNombre'],
                'telefono_cliente' => $validated['telefono'],
                'email_cliente' => $request->user()?->email,
                'tipo_entrega' => 'retiro',
                'direccion_envio' => $validated['direccion'] ?? null,
                'nota' => $validated['metodoPago'] ?? null,
                'items_json' => json_encode($validated['items'], JSON_UNESCAPED_UNICODE),
                'subtotal' => (float) $validated['total'],
                'costo_envio' => 0,
                'total' => (float) $validated['total'],
                'estado' => 'pendiente',
            ]);
        });

        return response()->json([
            'id' => $pedido->id,
            'estado' => $pedido->estado,
            'fecha' => optional($pedido->creado_en)->format('Y-m-d H:i:s'),
            'total' => (float) $pedido->total,
            'items' => $pedido->items,
        ], 201);
    }

    public function validarStock(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.producto_id' => ['required', 'integer'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
        ]);

        $result = $this->checkStock($validated['items']);

        if ($result['ok']) {
            return response()->json([
                'ok' => true,
                'items' => [],
            ]);
        }

        return response()->json([
            'ok' => false,
            'items' => $result['items'],
            'message' => 'Stock insuficiente para uno o mas productos.',
        ], 422);
    }

    private function checkStock(array $items): array
    {
        $productIds = collect($items)->pluck('producto_id')->map(fn ($id) => (int) $id)->all();
        $productos = Producto::query()
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $failed = [];

        foreach ($items as $item) {
            $productoId = (int) $item['producto_id'];
            $requested = (int) $item['cantidad'];
            /** @var Producto|null $producto */
            $producto = $productos->get($productoId);

            if (!$producto) {
                $failed[] = [
                    'producto_id' => $productoId,
                    'nombre' => $item['nombre'] ?? 'Producto',
                    'solicitado' => $requested,
                    'disponible' => 0,
                ];
                continue;
            }

            $available = (int) $producto->stock;
            if ($available < $requested) {
                $failed[] = [
                    'producto_id' => $productoId,
                    'nombre' => $producto->nombre,
                    'solicitado' => $requested,
                    'disponible' => $available,
                ];
            }
        }

        return [
            'ok' => empty($failed),
            'items' => $failed,
        ];
    }
}
