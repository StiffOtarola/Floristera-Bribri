<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CuentaController extends Controller
{
    /** Cliente (suscriptor) autenticado. */
    private function cliente()
    {
        return Auth::guard('web')->user();
    }

    // ══════════════════════════════════════════════════════
    // RESUMEN (dashboard del cliente)
    // ══════════════════════════════════════════════════════

    public function index()
    {
        $cliente = $this->cliente();

        // Volumen bajo en una florería: cargamos todo y derivamos stats en PHP.
        $pedidos = $cliente->todosLosPedidos()->get();

        $totalPedidos = $pedidos->count();
        $enProceso    = $pedidos->whereIn('estado', ['pendiente', 'confirmado', 'en_proceso', 'listo'])->count();
        $entregados   = $pedidos->where('estado', 'entregado')->count();
        $ultimos      = $pedidos->take(5);

        return view('cliente.dashboard', compact(
            'cliente', 'totalPedidos', 'enProceso', 'entregados', 'ultimos'
        ));
    }

    // ══════════════════════════════════════════════════════
    // LISTA DE PEDIDOS
    // ══════════════════════════════════════════════════════

    public function pedidos()
    {
        $cliente = $this->cliente();
        $pedidos = $cliente->todosLosPedidos()->get();

        return view('cliente.pedidos', compact('cliente', 'pedidos'));
    }

    // ══════════════════════════════════════════════════════
    // DETALLE DE UN PEDIDO (scopeado al cliente → sin IDOR)
    // ══════════════════════════════════════════════════════

    public function pedido(string $numero)
    {
        $cliente = $this->cliente();

        // Sólo busca dentro de los pedidos del propio cliente.
        $pedido = $cliente->todosLosPedidos()
            ->where('numero_pedido', $numero)
            ->first();

        abort_if(!$pedido, 404);

        return view('cliente.pedido', compact('cliente', 'pedido'));
    }

    // ══════════════════════════════════════════════════════
    // PERFIL
    // ══════════════════════════════════════════════════════

    public function perfil()
    {
        $cliente = $this->cliente();
        return view('cliente.perfil', compact('cliente'));
    }

    public function actualizarPerfil(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        $cliente = $this->cliente();
        $cliente->nombre = strip_tags(trim($request->input('nombre')));
        $cliente->save();

        return back()->with('success', 'Tus datos fueron actualizados.');
    }

    public function actualizarPassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required',
            'password'        => 'required|min:8|confirmed',
        ]);

        $cliente = $this->cliente();

        if (!Hash::check($request->input('password_actual'), $cliente->password_hash)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.']);
        }

        $cliente->password_hash = Hash::make($request->input('password'));
        $cliente->save();

        return back()->with('success', 'Tu contraseña fue cambiada.');
    }
}
