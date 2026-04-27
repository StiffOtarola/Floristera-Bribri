<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->query('estado');

        $query = Pedido::latest('creado_en');

        if ($estado && array_key_exists($estado, Pedido::ESTADOS)) {
            $query->where('estado', $estado);
        }

        $pedidos = $query->paginate(20)->withQueryString();

        return view('admin.pedidos.index', compact('pedidos', 'estado'));
    }

    public function detalle($id)
    {
        $pedido = Pedido::findOrFail($id);

        // $pedido->items ya viene del accessor getItemsAttribute()
        return view('admin.pedidos.detalle', compact('pedido'));
    }

    public function cambiarEstado(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        $request->validate([
            'estado' => 'required|in:' . implode(',', array_keys(Pedido::ESTADOS)),
        ]);

        $pedido->update(['estado' => $request->input('estado')]);

        return redirect()->route('admin.pedidos.detalle', $pedido->id)
            ->with('success', 'Estado actualizado.');
    }
}