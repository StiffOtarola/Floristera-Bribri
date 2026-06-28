@extends('layouts.main')
@section('title', 'Mis pedidos')

@section('content')
@include('cliente.partials.header', ['active' => 'pedidos'])

<div class="cuenta-wrap">
    <div class="c-card">
        <h2 style="margin-bottom:1.25rem;">Mis pedidos</h2>

        @forelse($pedidos as $pedido)
            @include('cliente.partials.pedido-row')
        @empty
            <div class="c-empty">
                <div class="emoji">🌷</div>
                <p style="margin:0.75rem 0 1.25rem;">Todavía no tenés pedidos.</p>
                <a href="{{ route('catalogo') }}" class="c-btn c-btn-primary">Ver el catálogo →</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
