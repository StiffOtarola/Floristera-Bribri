@extends('layouts.admin')
@section('page-title', 'Pedidos')

@push('css')
<style>
    .ped-table-desktop { display:block; }
    .ped-cards-mobile  { display:none; }

    .ped-card {
        background:white;border-radius:14px;padding:1rem;
        border:1px solid rgba(42,74,30,0.06);margin-bottom:1rem;
    }
    .ped-head { display:flex;align-items:center;justify-content:space-between;gap:0.5rem;margin-bottom:0.75rem; }
    .ped-num { font-weight:600;color:var(--verde);font-size:0.95rem; }
    .ped-body { display:flex;flex-direction:column;gap:0.4rem;font-size:0.875rem;color:var(--gris); }
    .ped-body strong { color:var(--texto); }
    .ped-row { display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap; }
    .ped-actions { display:flex;gap:0.5rem;margin-top:0.75rem; }
    .ped-actions .btn { flex:1;justify-content:center; }

    @media(max-width:900px) {
        .ped-table-desktop { display:none; }
        .ped-cards-mobile  { display:block; }
    }
</style>
@endpush

@section('content')

{{-- ═══ DESKTOP: Tabla ═══ --}}
<div class="ped-table-desktop">
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>N° Pedido</th>
                <th>Cliente</th>
                <th>Teléfono</th>
                <th>Entrega</th>
                <th>📅 Fecha retiro</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Creado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $p)
            <tr>
                <td><strong>{{ $p->numero_pedido }}</strong></td>
                <td>{{ $p->nombre_cliente }}</td>
                <td>
                    <a href="https://wa.me/506{{ $p->telefono_cliente }}" target="_blank" style="color:var(--verde);">
                        📱 {{ $p->telefono_cliente }}
                    </a>
                </td>
                <td>{{ $p->es_envio ? '🚗 Domicilio' : '🏪 Retiro' }}</td>
                <td>
                    @if($p->fecha_retiro)
                        @php
                            $esHoy   = $p->fecha_retiro->isToday();
                            $vencido = $p->fecha_retiro->isPast() && !$esHoy;
                        @endphp
                        <span class="badge {{ $esHoy ? 'badge-yellow' : ($vencido ? 'badge-red' : 'badge-blue') }}">
                            {{ $p->fecha_retiro_formateada }}
                            @if($esHoy) ⚠️ Hoy @endif
                        </span>
                    @else
                        <span style="color:var(--gris);font-size:0.83rem;">—</span>
                    @endif
                </td>
                <td><strong>{{ $p->total_formateado }}</strong></td>
                <td><span class="badge {{ $p->estado_badge }}">{{ $p->estado_label }}</span></td>
                <td style="color:var(--gris);font-size:0.83rem;">{{ $p->fecha_formateada }}</td>
                <td><a href="{{ route('admin.pedidos.detalle', $p->id) }}" class="btn btn-sm btn-outline">Ver detalle</a></td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center;color:var(--gris);padding:2rem;">Sin pedidos todavía</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>

{{-- ═══ MÓVIL: Tarjetas ═══ --}}
<div class="ped-cards-mobile">
    @forelse($pedidos as $p)
    <div class="ped-card">
        <div class="ped-head">
            <span class="ped-num">{{ $p->numero_pedido }}</span>
            <span class="badge {{ $p->estado_badge }}">{{ $p->estado_label }}</span>
        </div>
        <div class="ped-body">
            <div><strong>{{ $p->nombre_cliente }}</strong></div>
            <div class="ped-row">
                <a href="https://wa.me/506{{ $p->telefono_cliente }}" target="_blank" style="color:var(--verde);text-decoration:none;">📱 {{ $p->telefono_cliente }}</a>
                <span>{{ $p->es_envio ? '🚗 Domicilio' : '🏪 Retiro' }}</span>
            </div>
            <div class="ped-row">
                <strong>{{ $p->total_formateado }}</strong>
                @if($p->fecha_retiro)
                    @php
                        $esHoy   = $p->fecha_retiro->isToday();
                        $vencido = $p->fecha_retiro->isPast() && !$esHoy;
                    @endphp
                    <span class="badge {{ $esHoy ? 'badge-yellow' : ($vencido ? 'badge-red' : 'badge-blue') }}">
                        📅 {{ $p->fecha_retiro_formateada }}@if($esHoy) ⚠️@endif
                    </span>
                @endif
            </div>
            <div style="font-size:0.78rem;">{{ $p->fecha_formateada }}</div>
        </div>
        <div class="ped-actions">
            <a href="{{ route('admin.pedidos.detalle', $p->id) }}" class="btn btn-sm btn-outline">Ver detalle</a>
        </div>
    </div>
    @empty
    <div style="text-align:center;color:var(--gris);padding:3rem;">
        <div style="font-size:3rem;margin-bottom:1rem;">📦</div>
        <p>Sin pedidos todavía</p>
    </div>
    @endforelse
</div>

@if($pedidos->hasPages())
<div style="margin-top:1.25rem;display:flex;justify-content:center;">
    {{ $pedidos->links() }}
</div>
@endif

@endsection