@extends('layouts.main')
@section('title', $producto->nombre . ' — ' . config('floristeria.nombre'))

@push('css')
<style>
.breadcrumb-bar { background:var(--verde);padding:1rem 5%;font-size:0.85rem; }
.breadcrumb-bar a { color:rgba(255,255,255,0.7);text-decoration:none; }
.breadcrumb-bar span { color:rgba(255,255,255,0.4);margin:0 0.5rem; }
.breadcrumb-bar strong { color:white; }

.producto-wrap { max-width:1100px;margin:3rem auto;padding:0 5%;display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:start; }

/* Imagen */
.prod-imagen-wrap { border-radius:24px;overflow:hidden;background:linear-gradient(135deg,#e8f5e0,#d4edca);aspect-ratio:1;display:flex;align-items:center;justify-content:center;position:relative; }
.prod-imagen-wrap img { width:100%;height:100%;object-fit:cover; }
.prod-placeholder { font-size:8rem; }
.badge-dest { position:absolute;top:16px;left:16px;background:var(--terracota);color:white;font-size:0.75rem;padding:5px 14px;border-radius:100px;font-weight:500; }

/* Info */
.prod-cat-label { font-size:0.75rem;color:var(--terracota);text-transform:uppercase;letter-spacing:0.12em;font-weight:500;margin-bottom:0.5rem; }
.prod-title { font-family:'Cormorant Garamond',serif;font-size:2.4rem;font-weight:400;color:var(--verde);line-height:1.2;margin-bottom:1rem; }
.prod-precio { font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:600;color:var(--verde);margin-bottom:1.5rem; }
.prod-desc { font-size:0.95rem;color:var(--gris);line-height:1.8;margin-bottom:2rem; }

.stock-badge { display:inline-block;padding:4px 12px;border-radius:100px;font-size:0.78rem;font-weight:500;margin-bottom:1.5rem; }
.stock-disponible { background:#d4edda;color:#155724; }
.stock-pocas { background:#fff3cd;color:#856404; }
.stock-agotado { background:#f8d7da;color:#721c24; }

.qty-wrap { display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem; }
.qty-wrap label { font-size:0.875rem;font-weight:500; }
.qty-control { display:flex;align-items:center;border:1.5px solid rgba(42,74,30,0.2);border-radius:100px;overflow:hidden; }
.qty-btn { width:38px;height:38px;border:none;background:none;cursor:pointer;font-size:1.1rem;color:var(--verde);transition:background 0.2s; }
.qty-btn:hover { background:var(--crema); }
.qty-num { width:44px;text-align:center;font-weight:600;font-size:0.95rem; }

.btn-agregar { width:100%;background:var(--verde);color:white;border:none;cursor:pointer;padding:16px;border-radius:100px;font-family:'DM Sans',sans-serif;font-size:1rem;font-weight:500;transition:all 0.2s;margin-bottom:1rem; }
.btn-agregar:hover { background:var(--verde-claro);transform:translateY(-2px);box-shadow:0 8px 24px rgba(42,74,30,0.2); }
.btn-agregar:disabled { opacity:0.5;cursor:not-allowed;transform:none; }
.btn-agregar.ok { background:var(--terracota); }

.btn-wa { width:100%;display:flex;align-items:center;justify-content:center;gap:8px;background:#25D366;color:white;text-decoration:none;padding:14px;border-radius:100px;font-family:'DM Sans',sans-serif;font-size:0.9rem;font-weight:500;transition:all 0.2s; }
.btn-wa:hover { background:#1ebe5d;transform:translateY(-2px); }

/* Relacionados */
.relacionados { max-width:1100px;margin:3rem auto;padding:0 5% 4rem; }
.relacionados h2 { font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:400;color:var(--verde);margin-bottom:1.5rem; }
.rel-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.25rem; }
.rel-card { background:white;border-radius:16px;overflow:hidden;border:1px solid rgba(42,74,30,0.06);transition:all 0.3s;text-decoration:none;display:block; }
.rel-card:hover { transform:translateY(-4px);box-shadow:0 16px 32px rgba(42,74,30,0.1); }
.rel-img { height:160px;background:linear-gradient(135deg,#e8f5e0,#d4edca);display:flex;align-items:center;justify-content:center;font-size:3.5rem;overflow:hidden; }
.rel-img img { width:100%;height:100%;object-fit:cover; }
.rel-body { padding:1rem; }
.rel-name { font-family:'Cormorant Garamond',serif;font-size:1.05rem;font-weight:600;color:var(--verde);margin-bottom:4px; }
.rel-price { font-size:0.9rem;color:var(--terracota);font-weight:500; }

@media(max-width:768px){
    .producto-wrap { grid-template-columns:1fr;gap:2rem;margin:1.5rem auto; }
    .prod-title { font-size:1.8rem; }
    .rel-grid { grid-template-columns:repeat(2,1fr); }
}
@media(max-width:480px){
    .rel-grid { grid-template-columns:1fr; }
}
</style>
@endpush

@section('content')

<div class="breadcrumb-bar">
    <a href="{{ route('home') }}">Inicio</a>
    <span>→</span>
    <a href="{{ route('catalogo') }}">Catálogo</a>
    @if($producto->categoria)
    <span>→</span>
    <a href="{{ route('catalogo', ['categoria' => $producto->categoria_id]) }}">{{ $producto->categoria->nombre }}</a>
    @endif
    <span>→</span>
    <strong>{{ $producto->nombre }}</strong>
</div>

<div class="producto-wrap">

    {{-- Imagen --}}
    <div class="prod-imagen-wrap">
        @if($producto->imagen)
            <img src="{{ asset('storage/products/' . $producto->imagen) }}" alt="{{ $producto->nombre }}">
        @else
            <div class="prod-placeholder">💐</div>
        @endif
        @if($producto->destacado)
            <div class="badge-dest">⭐ Destacado</div>
        @endif
    </div>

    {{-- Info --}}
    <div>
        @if($producto->categoria)
        <div class="prod-cat-label">{{ $producto->categoria->nombre }}</div>
        @endif

        <h1 class="prod-title">{{ $producto->nombre }}</h1>

        <div class="prod-precio">{{ formatPrice($producto->precio) }}</div>

        @if($producto->stock > 10)
            <span class="stock-badge stock-disponible">✓ Disponible</span>
        @elseif($producto->stock > 0)
            <span class="stock-badge stock-pocas">⚠ Pocas unidades ({{ $producto->stock }} disponibles)</span>
        @else
            <span class="stock-badge stock-agotado">✕ Agotado</span>
        @endif

        @if($producto->descripcion)
        <p class="prod-desc">{{ $producto->descripcion }}</p>
        @endif

        @if($producto->stock !== 0)
        <div class="qty-wrap">
            <label>Cantidad:</label>
            <div class="qty-control">
                <button class="qty-btn" onclick="cambiarQty(-1)">−</button>
                <span class="qty-num" id="qty">1</span>
                <button class="qty-btn" onclick="cambiarQty(1)">+</button>
            </div>
        </div>

        <button class="btn-agregar" id="btnAgregar"
            onclick="addCart({{ $producto->id }}, '{{ e($producto->nombre) }}', {{ $producto->precio }}, this)">
            🛒 Agregar al carrito
        </button>
        @else
        <button class="btn-agregar" disabled>Producto agotado</button>
        @endif

        <a href="{{ whatsappLink('Hola, me interesa: ' . $producto->nombre . ' (' . formatPrice($producto->precio) . ')') }}"
           target="_blank" class="btn-wa">
            💬 Consultar por WhatsApp
        </a>
    </div>
</div>

{{-- Productos relacionados --}}
@if($relacionados->count() > 0)
<div class="relacionados">
    <h2>También te puede gustar</h2>
    <div class="rel-grid">
        @foreach($relacionados as $r)
        <a href="{{ route('catalogo.show', $r->id) }}" class="rel-card">
            <div class="rel-img">
                @if($r->imagen)
                    <img src="{{ asset('storage/products/' . $r->imagen) }}" alt="{{ $r->nombre }}">
                @else
                    💐
                @endif
            </div>
            <div class="rel-body">
                <div class="rel-name">{{ $r->nombre }}</div>
                <div class="rel-price">{{ formatPrice($r->precio) }}</div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

@endsection

@push('js')
<script>
let qty = 1;
const maxStock = {{ $producto->stock > 0 ? $producto->stock : 999 }};

function cambiarQty(delta) {
    qty = Math.max(1, Math.min(qty + delta, maxStock));
    document.getElementById('qty').textContent = qty;
}

function addCart(id, nombre, precio, btn) {
    btn.disabled = true;
    fetch('{{ route("api.carrito") }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({accion:'agregar', id, nombre, precio, cantidad: qty})
    }).then(r => r.json()).then(d => {
        if (d.success) {
            btn.textContent = '✓ Agregado al carrito';
            btn.classList.add('ok');
            showToast('✓ ' + nombre + ' agregado al carrito');
            const b = document.querySelector('.cart-badge');
            if (b) b.textContent = d.count;
            setTimeout(() => {
                btn.textContent = '🛒 Agregar al carrito';
                btn.classList.remove('ok');
                btn.disabled = false;
            }, 2000);
        } else {
            showToast(d.message || 'Error al agregar');
            btn.disabled = false;
        }
    });
}
</script>
@endpush
