@extends('layouts.admin')
@section('page-title', 'Dashboard')

@push('css')
<style>
    .dash-table-desktop { display:block; }
    .dash-cards-mobile  { display:none; }

    .dped-card {
        background:white;border-radius:14px;padding:1rem;
        border:1px solid rgba(42,74,30,0.06);margin-bottom:0.75rem;
    }
    .dped-head { display:flex;align-items:center;justify-content:space-between;gap:0.5rem;margin-bottom:0.5rem; }
    .dped-num { font-weight:600;color:var(--verde);font-size:0.9rem; }
    .dped-body { font-size:0.85rem;color:var(--gris);display:flex;flex-direction:column;gap:0.3rem; }
    .dped-body strong { color:var(--texto); }
    .dped-foot { display:flex;align-items:center;justify-content:space-between;gap:0.5rem;margin-top:0.75rem; }

    @media(max-width:900px) {
        .dash-table-desktop { display:none; }
        .dash-cards-mobile  { display:block; }
    }
    @media(max-width:640px){
        .section-title { font-size:1.2rem; }
    }
</style>
@endpush

@section('content')
<div class="stat-grid">
    <div class="stat-card"><div class="icon">📦</div><div class="num">{{ $stats['totalPedidos'] }}</div><div class="label">Total pedidos</div></div>
    <div class="stat-card"><div class="icon">⏳</div><div class="num">{{ $stats['pedidosPendientes'] }}</div><div class="label">Pendientes</div></div>
    <div class="stat-card"><div class="icon">🌸</div><div class="num">{{ $stats['totalProductos'] }}</div><div class="label">Productos activos</div></div>
    <div class="stat-card"><div class="icon">📧</div><div class="num">{{ $stats['totalSuscriptores'] }}</div><div class="label">Suscriptores</div></div>
    <div class="stat-card"><div class="icon">💰</div><div class="num" style="font-size:1.5rem;">{{ formatPrice($stats['ventasHoy']) }}</div><div class="label">Ventas hoy</div></div>
</div>

<h2 class="section-title">Últimos pedidos</h2>

{{-- ═══ DESKTOP: Tabla ═══ --}}
<div class="dash-table-desktop">
<div class="table-wrap">
    <table>
        <thead>
            <tr><th>Pedido</th><th>Cliente</th><th>Entrega</th><th>Total</th><th>Estado</th><th>Fecha</th><th></th></tr>
        </thead>
        <tbody>
            @forelse($ultimosPedidos as $p)
            @php
                $badges = ['pendiente'=>'badge-yellow','confirmado'=>'badge-blue','en_proceso'=>'badge-blue','listo'=>'badge-green','entregado'=>'badge-green','cancelado'=>'badge-red'];
                $labels = ['pendiente'=>'Pendiente','confirmado'=>'Confirmado','en_proceso'=>'En proceso','listo'=>'Listo','entregado'=>'Entregado','cancelado'=>'Cancelado'];
                $st = $p->estado;
            @endphp
            <tr>
                <td><strong>{{ $p->numero_pedido }}</strong></td>
                <td>{{ $p->nombre_cliente }}</td>
                <td>{{ $p->tipo_entrega === 'envio' ? '🚗 Domicilio' : '🏪 Retiro' }}</td>
                <td><strong>{{ formatPrice($p->total) }}</strong></td>
                <td><span class="badge {{ $badges[$st] ?? 'badge-gray' }}">{{ $labels[$st] ?? $st }}</span></td>
                <td style="color:var(--gris);font-size:0.85rem;">{{ \Carbon\Carbon::parse($p->creado_en)->format('d/m/Y H:i') }}</td>
                <td><a href="{{ route('admin.pedidos.detalle', $p->id) }}" class="btn btn-sm btn-outline">Ver</a></td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:var(--gris);padding:2rem;">No hay pedidos aún</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>

{{-- ═══ MÓVIL: Tarjetas ═══ --}}
<div class="dash-cards-mobile">
    @forelse($ultimosPedidos as $p)
    @php
        $badges = ['pendiente'=>'badge-yellow','confirmado'=>'badge-blue','en_proceso'=>'badge-blue','listo'=>'badge-green','entregado'=>'badge-green','cancelado'=>'badge-red'];
        $labels = ['pendiente'=>'Pendiente','confirmado'=>'Confirmado','en_proceso'=>'En proceso','listo'=>'Listo','entregado'=>'Entregado','cancelado'=>'Cancelado'];
        $st = $p->estado;
    @endphp
    <div class="dped-card">
        <div class="dped-head">
            <span class="dped-num">{{ $p->numero_pedido }}</span>
            <span class="badge {{ $badges[$st] ?? 'badge-gray' }}">{{ $labels[$st] ?? $st }}</span>
        </div>
        <div class="dped-body">
            <div><strong>{{ $p->nombre_cliente }}</strong></div>
            <div>{{ $p->tipo_entrega === 'envio' ? '🚗 Domicilio' : '🏪 Retiro' }} · <strong>{{ formatPrice($p->total) }}</strong></div>
            <div style="font-size:0.78rem;">{{ \Carbon\Carbon::parse($p->creado_en)->format('d/m/Y H:i') }}</div>
        </div>
        <div class="dped-foot">
            <a href="{{ route('admin.pedidos.detalle', $p->id) }}" class="btn btn-sm btn-outline">Ver detalle</a>
        </div>
    </div>
    @empty
    <div style="text-align:center;color:var(--gris);padding:2rem;">No hay pedidos aún</div>
    @endforelse
</div>
<div style="margin-top:1rem;text-align:right;">
    <a href="{{ route('admin.pedidos.index') }}" class="btn btn-outline">Ver todos los pedidos →</a>
</div>
@endsection