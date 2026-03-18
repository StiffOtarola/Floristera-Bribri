<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        $pedidos = Pedido::query()
            ->where('user_id', $user?->id)
            ->orderByDesc('id')
            ->limit(30)
            ->get()
            ->map(function (Pedido $pedido) {
                return [
                    'id' => $pedido->id,
                    'estado' => $pedido->estado,
                    'fecha' => optional($pedido->creado_en)->format('Y-m-d H:i:s'),
                    'total' => (float) $pedido->total,
                    'items' => $pedido->items,
                ];
            });

        return response()->json($pedidos);
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

        $pedido = Pedido::create([
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

        return response()->json([
            'id' => $pedido->id,
            'estado' => $pedido->estado,
            'fecha' => optional($pedido->creado_en)->format('Y-m-d H:i:s'),
            'total' => (float) $pedido->total,
            'items' => $pedido->items,
        ], 201);
    }
}
