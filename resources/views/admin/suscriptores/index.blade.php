@extends('layouts.admin')
@section('page-title', 'Suscriptores')
@section('top-actions')
    <a href="{{ route('admin.suscriptores.exportar') }}" class="btn btn-outline">⬇️ Exportar CSV</a>
@endsection

@push('css')
<style>
    .sus-table-desktop { display:block; }
    .sus-cards-mobile  { display:none; }

    .sus-card {
        background:white;border-radius:14px;padding:1rem;
        border:1px solid rgba(42,74,30,0.06);margin-bottom:0.75rem;
    }
    .sus-head { display:flex;align-items:center;justify-content:space-between;gap:0.5rem;margin-bottom:0.5rem; }
    .sus-name { font-weight:600;color:var(--verde);font-size:0.95rem; }
    .sus-body { font-size:0.85rem;color:var(--gris);display:flex;flex-direction:column;gap:0.3rem; }
    .sus-foot { display:flex;align-items:center;justify-content:space-between;gap:0.5rem;margin-top:0.75rem; }

    @media(max-width:900px) {
        .sus-table-desktop { display:none; }
        .sus-cards-mobile  { display:block; }
    }
</style>
@endpush

@section('content')

{{-- ═══ DESKTOP: Tabla ═══ --}}
<div class="sus-table-desktop">
<div class="table-wrap">
    <table>
        <thead><tr><th>Nombre</th><th>Email</th><th>Cuenta</th><th>Fecha</th><th></th></tr></thead>
        <tbody>
            @forelse($suscriptores as $s)
            <tr>
                <td>{{ $s->nombre }}</td>
                <td>{{ $s->email }}</td>
                <td><span class="badge {{ $s->password_hash ? 'badge-green' : 'badge-gray' }}">{{ $s->password_hash ? 'Con cuenta' : 'Solo suscrito' }}</span></td>
                <td style="color:var(--gris);font-size:0.83rem;">{{ \Carbon\Carbon::parse($s->suscrito_en)->format('d/m/Y') }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.suscriptores.eliminar', $s->id) }}" onsubmit="return confirm('¿Eliminar suscriptor?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:var(--gris);padding:2rem;">Sin suscriptores todavía</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>

{{-- ═══ MÓVIL: Tarjetas ═══ --}}
<div class="sus-cards-mobile">
    @forelse($suscriptores as $s)
    <div class="sus-card">
        <div class="sus-head">
            <span class="sus-name">{{ $s->nombre }}</span>
            <span class="badge {{ $s->password_hash ? 'badge-green' : 'badge-gray' }}">{{ $s->password_hash ? 'Con cuenta' : 'Solo suscrito' }}</span>
        </div>
        <div class="sus-body">
            <div>📧 {{ $s->email }}</div>
            <div style="font-size:0.78rem;">{{ \Carbon\Carbon::parse($s->suscrito_en)->format('d/m/Y') }}</div>
        </div>
        <div class="sus-foot">
            <form method="POST" action="{{ route('admin.suscriptores.eliminar', $s->id) }}" onsubmit="return confirm('¿Eliminar suscriptor?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">Eliminar</button>
            </form>
        </div>
    </div>
    @empty
    <div style="text-align:center;color:var(--gris);padding:3rem;">
        <div style="font-size:3rem;margin-bottom:1rem;">📧</div>
        <p>Sin suscriptores todavía</p>
    </div>
    @endforelse
</div>

@endsection