@php
    $cartCount     = getCartCount();
    $adminLogueado = Auth::guard('admin')->check();
    $userLogueado  = Auth::guard('web')->check();
    $adminNombre   = $adminLogueado ? Auth::guard('admin')->user()->nombre : null;
    $userNombre    = $userLogueado  ? Auth::guard('web')->user()->nombre   : null;
    $navSticky     = $adminLogueado || $userLogueado;

    $facebook  = config('floristeria.redes.facebook');
    $instagram = config('floristeria.redes.instagram');
    $tiktok    = config('floristeria.redes.tiktok');
    $hayRedes  = $facebook || $instagram || $tiktok;
@endphp

{{-- ═══════════════════════════════════════════════════════
     BARRA SUPERIOR (solo si está logueado)
     ═══════════════════════════════════════════════════════ --}}

@if($adminLogueado)
{{-- Barra admin --}}
<div style="
    background: var(--verde);
    color: rgba(255,255,255,0.9);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.8rem;
    padding: 0 5%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 38px;
    position: sticky;
    top: 0;
    z-index: 200;
">
    <span>🔐 Modo administrador — <strong>{{ $adminNombre }}</strong></span>
</div>

@elseif($userLogueado)
{{-- Barra cliente --}}
<div style="
    background: #F0EDE6;
    border-bottom: 1px solid rgba(42,74,30,0.1);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.8rem;
    padding: 0 5%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 36px;
    position: sticky;
    top: 0;
    z-index: 200;
">
    <a href="{{ route('cuenta.index') }}" style="color:var(--gris);text-decoration:none;">
        🌸 Hola, <strong style="color:var(--verde);">{{ $userNombre }}</strong> · <span style="text-decoration:underline;">Mi cuenta</span>
    </a>
    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit"
            style="background:none;border:none;cursor:pointer;color:var(--terracota);font-family:'DM Sans',sans-serif;font-size:0.75rem;padding:0;">
            Cerrar sesión
        </button>
    </form>
</div>

@else
{{-- Barra de redes sociales para invitados --}}
@if($hayRedes)
<div style="
    background: #0F1E09;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.78rem;
    padding: 0 5%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 36px;
    position: sticky;
    top: 0;
    z-index: 200;
">
    <span style="color:rgba(255,255,255,0.4);">{{ config('floristeria.emoji') }} {{ config('floristeria.slogan') }}</span>
</div>
@endif
@endif

{{-- ═══════════════════════════════════════════════════════
     NAVBAR PRINCIPAL
     ═══════════════════════════════════════════════════════ --}}
<nav id="navbar" style="
    width: 100%;
    z-index: 100;
    background: rgba(248,245,238,0.95);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(42,74,30,0.1);
    padding: 0 5%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 72px;
    transition: box-shadow 0.3s;
    @if($navSticky)
        position: sticky;
        top: {{ $adminLogueado ? '38px' : '36px' }};
    @elseif($hayRedes && !$adminLogueado && !$userLogueado)
        position: sticky;
        top: 36px;
    @else
        position: fixed;
        top: 0;
    @endif
">
    <a href="{{ route('home') }}" class="nav-logo">
        @if(file_exists(public_path('images/logo-marca.png')))
            <img src="{{ asset('images/logo-marca.png') }}?v={{ filemtime(public_path('images/logo-marca.png')) }}" alt="{{ config('floristeria.nombre') }}" class="nav-logo-img">
        @endif
        <span class="nav-logo-text">{{ config('floristeria.nombre') }}</span>
    </a>

    {{-- Menú de navegación principal --}}
    <ul class="nav-links" id="navLinks">
        <li><a href="{{ route('home') }}">Inicio</a></li>
        <li><a href="{{ route('catalogo') }}">Catálogo</a></li>
        <li><a href="{{ route('home') }}#entrega">Envíos</a></li>
        @if($userLogueado)
        <li><a href="{{ route('cuenta.index') }}">Mi cuenta</a></li>
        @endif

        {{-- Acciones de cuenta (solo dentro del menú móvil) --}}
        <li class="nav-auth-mobile">
            @if($adminLogueado)
                <a href="{{ route('admin.dashboard') }}" class="nav-menu-btn">⚙️ Panel admin</a>
                <form method="POST" action="{{ route('logout.admin') }}">
                    @csrf
                    <button type="submit" class="nav-menu-btn ghost">Cerrar sesión</button>
                </form>
            @elseif($userLogueado)
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-menu-btn ghost">Cerrar sesión</button>
                </form>
            @else
                <a href="{{ route('registro') }}" class="nav-menu-btn">🌸 Suscribirme</a>
                <a href="{{ route('login') }}" class="nav-menu-btn ghost">👤 Iniciar sesión</a>
            @endif
        </li>

        {{-- Redes sociales en menú móvil (solo visibles cuando el menú está abierto) --}}
        @if($hayRedes)
        <li style="padding-top:0.75rem;border-top:1px solid rgba(42,74,30,0.08);margin-top:0.5rem;display:none;" class="nav-redes-mobile">
            <div style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap;">
                @if($facebook)
                <a href="{{ $facebook }}" target="_blank" rel="noopener noreferrer"
                   style="color:var(--gris);font-size:0.85rem;display:flex;align-items:center;gap:6px;text-decoration:none;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="color:#1877F2">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    Facebook
                </a>
                @endif
                @if($instagram)
                <a href="{{ $instagram }}" target="_blank" rel="noopener noreferrer"
                   style="color:var(--gris);font-size:0.85rem;display:flex;align-items:center;gap:6px;text-decoration:none;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="color:#E1306C">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                    </svg>
                    Instagram
                </a>
                @endif
                @if($tiktok)
                <a href="{{ $tiktok }}" target="_blank" rel="noopener noreferrer"
                   style="color:var(--gris);font-size:0.85rem;display:flex;align-items:center;gap:6px;text-decoration:none;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="color:#010101">
                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                    </svg>
                    TikTok
                </a>
                @endif
            </div>
        </li>
        @endif
    </ul>

    {{-- Botones de acción --}}
    <div style="display:flex;align-items:center;gap:0.75rem;">

        <div class="nav-auth-desktop">
        @if($adminLogueado)
            <a href="{{ route('admin.dashboard') }}" class="nav-btn-admin">⚙️ Admin</a>
            <form method="POST" action="{{ route('logout.admin') }}" style="display:inline;">
                @csrf
                <button type="submit" class="nav-btn-ghost">Cerrar sesión</button>
            </form>

        @elseif($userLogueado)
            <a href="{{ route('cuenta.index') }}" class="nav-username" style="font-size:0.85rem;color:var(--verde);font-weight:500;white-space:nowrap;max-width:160px;overflow:hidden;text-overflow:ellipsis;text-decoration:none;" title="Mi cuenta">
                👤 {{ $userNombre }}
            </a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="nav-btn-ghost">Cerrar sesión</button>
            </form>

        @else
            <a href="{{ route('registro') }}" class="nav-btn-outline">🌸 Suscribirme</a>
            <a href="{{ route('login') }}" class="nav-btn-ghost">👤 Iniciar sesión</a>
        @endif
        </div>

        {{-- Carrito (siempre visible) --}}
        <a href="{{ route('carrito') }}" class="nav-cart">
            🛒
            @if($cartCount > 0)
                <span class="cart-badge">{{ $cartCount }}</span>
            @endif
        </a>

        <button class="hamburger" onclick="toggleMenu()">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

{{-- Spacer para cuando el nav es fixed --}}
@if(!$navSticky && !($hayRedes && !$adminLogueado && !$userLogueado))
    <div style="height:72px;"></div>
@elseif(!$navSticky && $hayRedes)
    <div style="height:72px;"></div>
@endif

{{-- Mostrar redes en menú móvil cuando se abre --}}
<style>
    /* Acciones de cuenta: barra en escritorio, dentro del menú en móvil */
    .nav-auth-desktop { display: flex; align-items: center; gap: 0.75rem; }
    .nav-auth-mobile  { display: none; }
    .nav-auth-mobile form { margin: 0; }
    .nav-menu-btn {
        display: block; width: 100%; text-align: center;
        padding: 13px 16px; border-radius: 100px;
        font-family: 'DM Sans', sans-serif; font-size: 0.95rem; font-weight: 500;
        text-decoration: none; cursor: pointer; border: none;
        background: var(--verde); color: #fff; margin-top: 0.6rem;
        transition: background 0.2s, border-color 0.2s;
    }
    .nav-menu-btn:hover { background: var(--verde-claro); }
    .nav-menu-btn.ghost { background: none; border: 1.5px solid rgba(42,74,30,0.25); color: var(--verde); }
    .nav-menu-btn.ghost:hover { border-color: var(--verde); background: none; }

    /* Logo en una sola línea, sin partirse */
    .nav-logo-text { white-space: nowrap; }

    @media(max-width:900px) {
        .nav-redes-mobile { display: block !important; }
        .nav-username     { display: none; }
        .nav-auth-desktop { display: none; }
        .nav-auth-mobile  { display: block; }
        .hamburger        { padding: 10px 6px; }   /* toque cómodo (>=44px) */
        .nav-logo-text    { font-size: 1.25rem; }
        .nav-logo-img     { height: 38px; }
    }
    @media(max-width:380px) {
        .nav-logo-text { font-size: 1.05rem; }
    }
</style>