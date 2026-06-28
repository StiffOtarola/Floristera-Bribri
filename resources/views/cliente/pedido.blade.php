@extends('layouts.main')
@section('title', 'Pedido ' . $pedido->numero_pedido)

@push('css')
<style>
    .back-link { display: inline-block; color: var(--gris); text-decoration: none; font-size: 0.85rem; margin-bottom: 1rem; }
    .back-link:hover { color: var(--verde); }
    .item-line { display: flex; justify-content: space-between; gap: 1rem; padding: 0.85rem 0; border-bottom: 1px solid rgba(42,74,30,0.07); }
    .item-line:last-child { border-bottom: none; }
    .item-name { font-size: 0.92rem; }
    .item-qty { font-size: 0.78rem; color: var(--gris); }
    .totals { margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid rgba(42,74,30,0.12); }
    .totals .row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 0.9rem; color: var(--gris); }
    .totals .row.grand { font-size: 1.15rem; color: var(--verde); font-weight: 600; margin-top: 6px; }
</style>
@endpush

@section('content')
@include('cliente.partials.header', ['active' => 'pedidos'])

@php
    $colones = fn ($n) => '₡' . number_format((float) $n, 0, ',', '.');
@endphp

<div class="cuenta-wrap">
    <a href="{{ route('cuenta.pedidos') }}" class="back-link">← Volver a mis pedidos</a>

    <div class="c-card">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem;">
            <div>
                <div class="pedido-num" style="font-size:1.2rem;">{{ $pedido->numero_pedido }}</div>
                <div class="pedido-meta">Realizado el {{ $pedido->fecha_formateada }}</div>
            </div>
            <span class="badge {{ $pedido->estado_badge }}">{{ $pedido->estado_label }}</span>
        </div>

        {{-- Seguimiento del estado --}}
        <div style="margin-bottom:1.75rem;">
            @include('cliente.partials.seguimiento')
        </div>

        <div class="c-info-grid" style="margin-bottom:1.75rem;">
            <div>
                <div class="k">Tipo de entrega</div>
                <div class="v">{{ $pedido->es_envio ? '🚚 Envío a domicilio' : '🏪 Retiro en tienda' }}</div>
            </div>
            <div>
                <div class="k">Fecha de {{ $pedido->es_envio ? 'envío' : 'retiro' }}</div>
                <div class="v">{{ $pedido->fecha_retiro_formateada }}</div>
            </div>
            @if($pedido->es_envio && $pedido->direccion_envio)
            <div style="grid-column:1 / -1;">
                <div class="k">Dirección</div>
                <div class="v">{{ $pedido->direccion_envio }}</div>
            </div>
            @endif
            @if($pedido->nota)
            <div style="grid-column:1 / -1;">
                <div class="k">Nota</div>
                <div class="v">{{ $pedido->nota }}</div>
            </div>
            @endif
        </div>

        <h2 style="font-size:1.25rem;margin-bottom:0.5rem;">Productos</h2>
        @foreach($pedido->items as $item)
            <div class="item-line">
                <div>
                    <div class="item-name">{{ $item['nombre'] ?? 'Producto' }}</div>
                    <div class="item-qty">{{ $colones($item['precio'] ?? 0) }} × {{ $item['cantidad'] ?? 1 }}</div>
                </div>
                <div style="font-weight:500;white-space:nowrap;">{{ $colones(($item['precio'] ?? 0) * ($item['cantidad'] ?? 1)) }}</div>
            </div>
        @endforeach

        <div class="totals">
            <div class="row"><span>Subtotal</span><span>{{ $colones($pedido->subtotal) }}</span></div>
            @if($pedido->costo_envio > 0)
            <div class="row"><span>Envío</span><span>{{ $colones($pedido->costo_envio) }}</span></div>
            @endif
            <div class="row grand"><span>Total</span><span>{{ $pedido->total_formateado }}</span></div>
        </div>

        <form method="POST" action="{{ route('cuenta.reordenar', $pedido->numero_pedido) }}" style="margin-top:1.5rem;">
            @csrf
            <button type="submit" class="c-btn c-btn-primary" style="width:100%;">🛒 Volver a pedir esto</button>
        </form>
    </div>
</div>
@endsection
