<?php

namespace App\Console\Commands;

use App\Models\Pedido;
use Illuminate\Console\Command;

class EliminarPedidosVencidos extends Command
{
    protected $signature   = 'pedidos:limpiar';
    protected $description = 'Elimina todos los pedidos cuya fecha_retiro ya pasó (1 día posterior)';

    public function handle(): int
    {
        // Solo borra pedidos ENTREGADOS o CANCELADOS con más de 30 días
        // Los pedidos activos (pendiente, confirmado, en_proceso, listo) nunca se borran
        $pedidos = Pedido::whereIn('estado', ['entregado', 'cancelado'])
            ->where('creado_en', '<', now()->subDays(15))
            ->get();

        if ($pedidos->isEmpty()) {
            $this->info('No hay pedidos antiguos para eliminar.');
            return self::SUCCESS;
        }

        $total = $pedidos->count();

        foreach ($pedidos as $pedido) {
            $this->line("  → Eliminando #{$pedido->numero_pedido} ({$pedido->estado} — {$pedido->fecha_formateada})");
            $pedido->delete();
        }

        $this->info("✅ {$total} pedido(s) eliminado(s) correctamente.");
        return self::SUCCESS;
    }
}