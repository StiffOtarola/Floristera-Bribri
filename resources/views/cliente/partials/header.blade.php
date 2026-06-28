{{-- Encabezado + pestañas de "Mi cuenta". Recibe $cliente y $active. --}}
@once
@push('css')
<style>
    .cuenta-hero { background: var(--verde); color: #fff; padding: 2.5rem 5% 2.25rem; }
    .cuenta-hero-inner { max-width: 1000px; margin: 0 auto; }
    .cuenta-eyebrow { text-transform: uppercase; letter-spacing: 0.18em; font-size: 0.72rem; opacity: 0.65; margin-bottom: 0.35rem; }
    .cuenta-hero h1 { font-family: 'Cormorant Garamond', serif; font-weight: 400; font-size: 2.3rem; line-height: 1.1; }

    .cuenta-tabs { background: #fff; border-bottom: 1px solid rgba(42,74,30,0.1); position: sticky; top: 0; z-index: 50; }
    .cuenta-tabs-inner { max-width: 1000px; margin: 0 auto; display: flex; gap: 0.25rem; padding: 0 5%; overflow-x: auto; }
    .cuenta-tabs a { padding: 1rem 1.1rem; font-size: 0.85rem; color: var(--gris); text-decoration: none; border-bottom: 2px solid transparent; transition: all 0.2s; white-space: nowrap; }
    .cuenta-tabs a:hover { color: var(--verde); }
    .cuenta-tabs a.active { color: var(--verde); border-bottom-color: var(--verde); font-weight: 500; }

    .cuenta-wrap { max-width: 1000px; margin: 0 auto; padding: 2.25rem 5% 4rem; }

    .c-card { background: #fff; border: 1px solid rgba(42,74,30,0.06); border-radius: 20px; padding: 1.75rem; box-shadow: 0 10px 30px rgba(42,74,30,0.05); }
    .c-card + .c-card { margin-top: 1.5rem; }
    .c-card h2 { font-family: 'Cormorant Garamond', serif; font-weight: 400; color: var(--verde); font-size: 1.55rem; }

    .c-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.75rem; }
    .c-stat { background: #fff; border: 1px solid rgba(42,74,30,0.06); border-radius: 18px; padding: 1.35rem 1rem; text-align: center; }
    .c-stat .n { font-family: 'Cormorant Garamond', serif; font-size: 2.1rem; color: var(--verde); line-height: 1; }
    .c-stat .l { font-size: 0.74rem; color: var(--gris); margin-top: 0.4rem; text-transform: uppercase; letter-spacing: 0.06em; }

    .pedido-row { display: flex; align-items: center; gap: 1rem; padding: 1.05rem 1.2rem; border: 1px solid rgba(42,74,30,0.08); border-radius: 16px; margin-bottom: 0.85rem; background: #fff; transition: border-color 0.2s, box-shadow 0.2s; flex-wrap: wrap; }
    .pedido-row:hover { border-color: var(--verde); box-shadow: 0 6px 18px rgba(42,74,30,0.06); }
    .pedido-row .grow { flex: 1; min-width: 150px; }
    .pedido-num { font-weight: 600; color: var(--verde); font-size: 0.95rem; }
    .pedido-meta { font-size: 0.8rem; color: var(--gris); margin-top: 2px; }
    .pedido-total { font-weight: 600; white-space: nowrap; }

    .badge { display: inline-block; padding: 4px 12px; border-radius: 100px; font-size: 0.72rem; font-weight: 600; white-space: nowrap; }
    .badge-yellow { background: #FEF3C7; color: #92400E; }
    .badge-blue   { background: #DBEAFE; color: #1E40AF; }
    .badge-green  { background: #D1FAE5; color: #065F46; }
    .badge-red    { background: #FEE2E2; color: #991B1B; }
    .badge-gray   { background: #E5E7EB; color: #374151; }

    .c-btn { display: inline-block; padding: 9px 18px; border-radius: 100px; font-family: 'DM Sans', sans-serif; font-size: 0.82rem; font-weight: 500; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; white-space: nowrap; }
    .c-btn-primary { background: var(--verde); color: #fff; }
    .c-btn-primary:hover { background: var(--verde-claro); transform: translateY(-1px); }
    .c-btn-ghost { background: none; border: 1.5px solid rgba(42,74,30,0.2); color: var(--gris); }
    .c-btn-ghost:hover { border-color: var(--verde); color: var(--verde); }

    .c-empty { text-align: center; padding: 2.75rem 1rem; color: var(--gris); }
    .c-empty .emoji { font-size: 3rem; }

    .c-alert { padding: 12px 16px; border-radius: 12px; font-size: 0.85rem; margin-bottom: 1.25rem; }
    .c-alert-error { background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; }

    .c-form-group { margin-bottom: 1.15rem; }
    .c-form-group label { display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 6px; }
    .c-form-group input { width: 100%; padding: 12px 15px; border: 1.5px solid rgba(42,74,30,0.15); border-radius: 12px; font-family: 'DM Sans', sans-serif; font-size: 0.9rem; outline: none; background: var(--crema); transition: border 0.2s; }
    .c-form-group input:focus { border-color: var(--verde); background: #fff; }
    .c-form-group input:disabled { opacity: 0.55; cursor: not-allowed; }
    .c-form-hint { font-size: 0.76rem; color: var(--gris); margin-top: 5px; }

    .c-info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.1rem 1.5rem; }
    .c-info-grid .k { font-size: 0.74rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gris); margin-bottom: 3px; }
    .c-info-grid .v { font-size: 0.95rem; color: var(--texto); }

    @media(max-width:640px) {
        .c-stats { grid-template-columns: 1fr 1fr; }
        .cuenta-hero h1 { font-size: 1.9rem; }
        .c-info-grid { grid-template-columns: 1fr; }
        .pedido-row .c-btn { width: 100%; text-align: center; }
    }
</style>
@endpush
@endonce

<div class="cuenta-hero">
    <div class="cuenta-hero-inner">
        <p class="cuenta-eyebrow">Mi cuenta</p>
        <h1>Hola, {{ $cliente->nombre }} {{ config('floristeria.emoji', '🌸') }}</h1>
    </div>
</div>

<div class="cuenta-tabs">
    <div class="cuenta-tabs-inner">
        <a href="{{ route('cuenta.index') }}"   class="{{ ($active ?? '') === 'resumen' ? 'active' : '' }}">Resumen</a>
        <a href="{{ route('cuenta.pedidos') }}" class="{{ ($active ?? '') === 'pedidos' ? 'active' : '' }}">Mis pedidos</a>
        <a href="{{ route('cuenta.perfil') }}"  class="{{ ($active ?? '') === 'perfil'  ? 'active' : '' }}">Mi perfil</a>
    </div>
</div>
