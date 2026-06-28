{{-- Fila de un pedido. Recibe $pedido. --}}
<div class="pedido-row">
    <div class="grow">
        <div class="pedido-num">{{ $pedido->numero_pedido }}</div>
        <div class="pedido-meta">{{ $pedido->fecha_formateada }} · {{ count($pedido->items) }} artículo(s)</div>
    </div>
    <span class="badge {{ $pedido->estado_badge }}">{{ $pedido->estado_label }}</span>
    <div class="pedido-total">{{ $pedido->total_formateado }}</div>
    <div class="pedido-actions">
        <a href="{{ route('cuenta.pedido', $pedido->numero_pedido) }}" class="c-btn c-btn-ghost">Ver detalle</a>
        <form method="POST" action="{{ route('cuenta.reordenar', $pedido->numero_pedido) }}">
            @csrf
            <button type="submit" class="c-btn c-btn-primary">🛒 Volver a pedir</button>
        </form>
    </div>
</div>
