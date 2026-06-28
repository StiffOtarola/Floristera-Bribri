@extends('layouts.main')
@section('title', 'Mi cuenta')

@section('content')
@include('cliente.partials.header', ['active' => 'resumen'])

<div class="cuenta-wrap">

    <div class="c-stats">
        <div class="c-stat">
            <div class="n">{{ $totalPedidos }}</div>
            <div class="l">Pedidos</div>
        </div>
        <div class="c-stat">
            <div class="n">{{ $enProceso }}</div>
            <div class="l">En curso</div>
        </div>
        <div class="c-stat">
            <div class="n">{{ $entregados }}</div>
            <div class="l">Entregados</div>
        </div>
    </div>

    <div class="c-card">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;margin-bottom:1.25rem;">
            <h2>Últimos pedidos</h2>
            @if($totalPedidos > 0)
                <a href="{{ route('cuenta.pedidos') }}" class="c-btn c-btn-ghost">Ver todos</a>
            @endif
        </div>

        @forelse($ultimos as $pedido)
            @include('cliente.partials.pedido-row')
        @empty
            <div class="c-empty">
                <div class="emoji">🌷</div>
                <p style="margin:0.75rem 0 1.25rem;">Todavía no tenés pedidos.<br>¡Tu primer ramo te está esperando!</p>
                <a href="{{ route('catalogo') }}" class="c-btn c-btn-primary">Ver el catálogo →</a>
            </div>
        @endforelse
    </div>

</div>
@endsection
