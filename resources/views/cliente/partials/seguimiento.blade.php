{{-- Barra de seguimiento del estado del pedido. Recibe $pedido. --}}
@once
@push('css')
<style>
    .track-cancel { background:#FEE2E2; color:#991B1B; border:1px solid #FCA5A5; border-radius:14px; padding:14px 16px; font-size:0.9rem; font-weight:500; text-align:center; }
    .track { display:flex; margin:0.25rem 0 0.5rem; }
    .track-step { flex:1; text-align:center; position:relative; min-width:0; }
    .track-step .dot { width:38px; height:38px; border-radius:50%; background:#E5E7EB; color:#9CA3AF; display:flex; align-items:center; justify-content:center; margin:0 auto 8px; font-size:0.95rem; position:relative; z-index:2; border:3px solid #fff; transition:all 0.2s; }
    .track-step .lbl { font-size:0.72rem; color:var(--gris); line-height:1.2; }
    /* línea conectora hacia el paso anterior */
    .track-step::before { content:''; position:absolute; top:18px; left:-50%; width:100%; height:3px; background:#E5E7EB; z-index:1; }
    .track-step:first-child::before { display:none; }
    /* completados y actual */
    .track-step.done .dot { background:var(--verde); color:#fff; }
    .track-step.done::before,
    .track-step.current::before { background:var(--verde); }
    .track-step.current .dot { background:var(--verde); color:#fff; box-shadow:0 0 0 4px rgba(42,74,30,0.15); }
    .track-step.current .lbl { color:var(--verde); font-weight:600; }
    @media(max-width:480px){
        .track-step .dot { width:32px; height:32px; font-size:0.85rem; }
        .track-step::before { top:15px; }
        .track-step .lbl { font-size:0.65rem; }
    }
</style>
@endpush
@endonce

@php
    $flujo = ['pendiente', 'confirmado', 'en_proceso', 'listo', 'entregado'];
    $pasos = [
        'pendiente'  => ['Pendiente', '📝'],
        'confirmado' => ['Confirmado', '✅'],
        'en_proceso' => ['En proceso', '🌸'],
        'listo'      => ['Listo', '🎁'],
        'entregado'  => [$pedido->es_envio ? 'Entregado' : 'Retirado', '🚚'],
    ];
    $cancelado = $pedido->estado === 'cancelado';
    $actualIdx = array_search($pedido->estado, $flujo);
@endphp

@if($cancelado)
    <div class="track-cancel">✕ Este pedido fue cancelado.</div>
@else
    <div class="track">
        @foreach($flujo as $i => $key)
            <div class="track-step {{ $i < $actualIdx ? 'done' : ($i === $actualIdx ? 'current' : '') }}">
                <div class="dot">{{ $i < $actualIdx ? '✓' : $pasos[$key][1] }}</div>
                <div class="lbl">{{ $pasos[$key][0] }}</div>
            </div>
        @endforeach
    </div>
@endif
