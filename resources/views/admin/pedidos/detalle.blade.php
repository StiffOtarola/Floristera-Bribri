@extends('layouts.admin')
@section('page-title', 'Pedido ' . $pedido->numero_pedido)
@section('top-actions')
    <a href="{{ route('admin.pedidos.index') }}" class="btn btn-outline">&#8592; Volver</a>
@endsection

@push('css')
<style>
    /* ── Layout principal ─────────────────────────────── */
    .pedido-grid {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 1.5rem;
        align-items: start;
    }

    /* ── Elimina min-width:600px global dentro del form-card ── */
    .form-card table { min-width: 0; }

    /* ── Títulos de sección ───────────────────────────── */
    .sec-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--verde);
        margin-bottom: 1rem;
        padding-bottom: 0.6rem;
        border-bottom: 1px solid rgba(42,74,30,0.08);
    }

    /* ── Tabla de info (etiqueta + valor) ────────────── */
    .info-tbl { width: 100%; border-collapse: collapse; }
    .info-tbl td {
        padding: 7px 0;
        font-size: 0.875rem;
        vertical-align: top;
    }
    .info-tbl .lbl {
        color: var(--gris);
        font-size: 0.8rem;
        width: 130px;
        min-width: 130px;
        padding-right: 12px;
        white-space: nowrap;
    }

    /* ── Tabla de productos ───────────────────────────── */
    .prod-tbl { width: 100%; border-collapse: collapse; }
    .prod-tbl td { padding: 9px 0; font-size: 0.875rem; vertical-align: middle; }
    .prod-tbl tr { border-bottom: 1px solid rgba(42,74,30,0.06); }
    .prod-tbl tr:last-child { border-bottom: none; }
    .prod-tbl .c-cant { color: var(--gris); text-align: center; width: 44px; }
    .prod-tbl .c-price { font-weight: 500; text-align: right; white-space: nowrap; }
    .prod-tbl .c-name { padding-right: 8px; }
    .prod-tbl .c-sub { color: var(--gris); }

    .total-row { border-top: 1.5px solid rgba(42,74,30,0.1) !important; }
    .total-row td { font-weight: 600; color: var(--verde); padding-top: 12px; }
    .total-amount {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--verde);
        text-align: right;
        white-space: nowrap;
    }

    /* ── Sidebar sticky en desktop ───────────────────── */
    .accion-col { position: sticky; top: 80px; }

    /* ════════════════════════════════════════════════════
       RESPONSIVE — Tablet (≤ 900 px)
       ════════════════════════════════════════════════════ */
    @media (max-width: 900px) {
        .pedido-grid { grid-template-columns: 1fr; }
        .accion-col  { position: static; }
    }

    /* ════════════════════════════════════════════════════
       RESPONSIVE — Móvil grande (≤ 640 px)
       ════════════════════════════════════════════════════ */
    @media (max-width: 640px) {
        .form-card { padding: 1.25rem; }
        .info-tbl .lbl { width: 110px; min-width: 110px; font-size: 0.78rem; }
        .total-amount { font-size: 1.1rem; }
    }

    /* ════════════════════════════════════════════════════
       RESPONSIVE — Móvil pequeño (≤ 480 px)
       Las filas de info se apilan: etiqueta arriba, valor abajo
       ════════════════════════════════════════════════════ */
    @media (max-width: 480px) {
        .form-card { padding: 1rem; }
        .sec-title { font-size: 0.95rem; }

        /* Info apilada */
        .info-tbl tr    { display: block; padding: 8px 0; border-bottom: 1px solid rgba(42,74,30,0.05); }
        .info-tbl tr:last-child { border-bottom: none; }
        .info-tbl td    { display: block; padding: 0; width: 100% !important; min-width: 0 !important; }
        .info-tbl .lbl  { font-size: 0.7rem; letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 2px; white-space: normal; }

        /* Productos: nombre ocupa toda la fila, cantidad y precio se comprimen */
        .prod-tbl .c-name { font-size: 0.82rem; padding-right: 4px; }
        .prod-tbl .c-cant { width: 36px; font-size: 0.82rem; }
        .prod-tbl .c-price { font-size: 0.82rem; }
        .total-amount { font-size: 1rem; }
    }

    /* ════════════════════════════════════════════════════
       RESPONSIVE — Móvil muy pequeño (≤ 360 px)
       ════════════════════════════════════════════════════ */
    @media (max-width: 360px) {
        .form-card { padding: 0.875rem; }
        .prod-tbl td { padding: 7px 0; font-size: 0.78rem; }
    }
</style>
@endpush

@section('content')
<div class="pedido-grid">

    {{-- ════ COLUMNA PRINCIPAL ════ --}}
    <div>
        <div class="form-card" style="max-width:100%;">

            {{-- Detalle del pedido --}}
            <div class="sec-title">&#128230; Detalle del pedido</div>
            <table class="info-tbl">
                <tr>
                    <td class="lbl">N&deg; Pedido</td>
                    <td><strong>{{ $pedido->numero_pedido }}</strong></td>
                </tr>
                <tr>
                    <td class="lbl">Estado</td>
                    <td><span class="badge {{ $pedido->estado_badge }}">{{ $pedido->estado_label }}</span></td>
                </tr>
                <tr>
                    <td class="lbl">Fecha retiro</td>
                    <td>
                        @if($pedido->fecha_retiro)
                            @php
                                $esHoy   = $pedido->fecha_retiro->isToday();
                                $vencido = $pedido->fecha_retiro->isPast() && !$esHoy;
                            @endphp
                            <span class="badge {{ $esHoy ? 'badge-yellow' : ($vencido ? 'badge-red' : 'badge-green') }}">
                                {{ $pedido->fecha_retiro_formateada }}
                                @if($esHoy) &mdash; <strong>Hoy</strong>
                                @elseif($vencido) &mdash; Vencido
                                @endif
                            </span>
                        @else
                            <span style="color:var(--gris);">Sin fecha asignada</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="lbl">Fecha creaci&oacute;n</td>
                    <td>{{ $pedido->fecha_formateada }}</td>
                </tr>
            </table>

            <hr style="border:none;border-top:1px solid rgba(42,74,30,0.08);margin:1.25rem 0;">

            {{-- Datos del cliente --}}
            <div class="sec-title">&#128100; Datos del cliente</div>
            <table class="info-tbl">
                <tr>
                    <td class="lbl">Nombre</td>
                    <td>{{ $pedido->nombre_cliente }}</td>
                </tr>
                <tr>
                    <td class="lbl">Tel&eacute;fono</td>
                    <td>
                        <a href="https://wa.me/506{{ $pedido->telefono_cliente }}" target="_blank"
                           style="color:var(--verde);font-weight:500;">
                            {{ $pedido->telefono_cliente }}
                        </a>
                    </td>
                </tr>
                @if($pedido->email_cliente)
                <tr>
                    <td class="lbl">Email</td>
                    <td style="word-break:break-all;">{{ $pedido->email_cliente }}</td>
                </tr>
                @endif
                <tr>
                    <td class="lbl">Tipo entrega</td>
                    <td>{{ $pedido->es_envio ? 'Domicilio' : 'Retiro en local' }}</td>
                </tr>
                @if($pedido->direccion_envio)
                <tr>
                    <td class="lbl">Direcci&oacute;n</td>
                    <td>{{ $pedido->direccion_envio }}</td>
                </tr>
                @endif
                @if($pedido->nota)
                <tr>
                    <td class="lbl">Nota</td>
                    <td style="font-style:italic;">{{ $pedido->nota }}</td>
                </tr>
                @endif
            </table>

            <hr style="border:none;border-top:1px solid rgba(42,74,30,0.08);margin:1.25rem 0;">

            {{-- Productos --}}
            <div class="sec-title">&#128722; Productos</div>
            <table class="prod-tbl">
                @foreach($pedido->items as $item)
                <tr>
                    <td class="c-name">{{ $item['nombre'] }}</td>
                    <td class="c-cant">x{{ (int)$item['cantidad'] }}</td>
                    <td class="c-price">{{ formatPrice($item['precio'] * $item['cantidad']) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="c-sub">Subtotal</td>
                    <td class="c-price">{{ formatPrice($pedido->subtotal) }}</td>
                </tr>
                @if($pedido->costo_envio > 0)
                <tr>
                    <td colspan="2" class="c-sub">Env&iacute;o</td>
                    <td class="c-price">{{ formatPrice($pedido->costo_envio) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td colspan="2">TOTAL</td>
                    <td class="total-amount">{{ $pedido->total_formateado }}</td>
                </tr>
            </table>

        </div>
    </div>

    {{-- ════ COLUMNA DE ACCIONES ════ --}}
    <div class="accion-col">
        <div class="form-card" style="max-width:100%;">

            <div class="sec-title">&#128260; Actualizar estado</div>
            <form method="POST" action="{{ route('admin.pedidos.estado', $pedido->id) }}">
                @csrf @method('PATCH')
                <div class="form-group">
                    <select name="estado">
                        @foreach(\App\Models\Pedido::ESTADOS as $val => $lbl)
                        <option value="{{ $val }}" {{ $pedido->estado === $val ? 'selected' : '' }}>
                            {{ $lbl }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">
                    Guardar estado
                </button>
            </form>

            <hr style="border:none;border-top:1px solid rgba(42,74,30,0.08);margin:1.25rem 0;">

            <a href="https://wa.me/506{{ $pedido->telefono_cliente }}"
               target="_blank"
               class="btn"
               style="width:100%;background:#25D366;color:white;justify-content:center;">
                Contactar por WhatsApp
            </a>

        </div>
    </div>

</div>
@endsection
