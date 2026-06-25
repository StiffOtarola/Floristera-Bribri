<?php

// ══════════════════════════════════════════════════════════
// CONFIGURACIÓN DEL NEGOCIO — 100% desde .env
// ══════════════════════════════════════════════════════════
// Uso en código:   config('floristeria.nombre')
// Uso en Blade:    {{ config('floristeria.whatsapp') }}
// ══════════════════════════════════════════════════════════

return [

    // ── Identidad del negocio ─────────────────────────────
    'nombre'      => env('TIENDA_NOMBRE',  'Mi Tienda'),
    'slogan'      => env('TIENDA_SLOGAN',  'La mejor tienda en línea'),
    'descripcion' => env('TIENDA_DESC',    'Productos de calidad directo a tu puerta'),
    'admin_email' => env('TIENDA_ADMIN_EMAIL', 'admin@ejemplo.com'),
    'emoji'       => env('TIENDA_EMOJI',   '🛒'),

    // ── Colores (CSS hex sin #) ───────────────────────────
    // Cambiar estos 5 valores cambia TODO el color del sitio
    'colores' => [
        'primario'       => env('TIENDA_COLOR_PRIMARIO',       '2A4A1E'),
        'primario_claro' => env('TIENDA_COLOR_PRIMARIO_CLARO', '4A7A35'),
        'acento'         => env('TIENDA_COLOR_ACENTO',         'C4714A'),
        'rosa'           => env('TIENDA_COLOR_ROSA',           'E8B4A0'),
        'fondo'          => env('TIENDA_COLOR_FONDO',          'F8F5EE'),
        'dorado'         => env('TIENDA_COLOR_DORADO',         'C9A86A'),
    ],

    // ── Contacto ──────────────────────────────────────────
    'whatsapp'   => env('TIENDA_WHATSAPP',   '50600000000'),
    'telefono'   => env('TIENDA_TELEFONO',   ''),
    'direccion'  => env('TIENDA_DIRECCION',  'Costa Rica'),
    'horario'    => env('TIENDA_HORARIO',    'Lunes a Sábado: 8:00am - 6:00pm'),
    'maps_url'   => env('TIENDA_MAPS_URL',   ''),
    'maps_embed' => env('TIENDA_MAPS_EMBED', ''),

    // ── Envío ─────────────────────────────────────────────
    'costo_envio'        => env('TIENDA_COSTO_ENVIO',        3000),
    'envio_gratis_desde' => env('TIENDA_ENVIO_GRATIS_DESDE', 0),

    // ── Moneda ────────────────────────────────────────────
    'moneda' => [
        'simbolo'       => env('TIENDA_MONEDA_SIMBOLO',  '₡'),
        'decimales'     => (int) env('TIENDA_MONEDA_DECIMALES', 0),
        'separador_dec' => env('TIENDA_MONEDA_SEP_DEC',  ','),
        'separador_mil' => env('TIENDA_MONEDA_SEP_MIL',  '.'),
        'codigo'        => env('TIENDA_MONEDA_CODIGO',   'CRC'),
    ],

    // ── Pedidos ───────────────────────────────────────────
    'prefijo_pedido' => env('TIENDA_PREFIJO_PEDIDO', 'TDA'),
    'estados_pedido' => [
        'pendiente'  => '🟡 Pendiente',
        'confirmado' => '🔵 Confirmado',
        'en_proceso' => '🟠 En proceso',
        'listo'      => '🟢 Listo',
        'entregado'  => '✅ Entregado',
        'cancelado'  => '🔴 Cancelado',
    ],

    // ── Productos ─────────────────────────────────────────
    'productos' => [
        'destacados_home' => (int) env('TIENDA_DESTACADOS_HOME', 6),
        'imagen_max_kb'   => (int) env('TIENDA_IMAGEN_MAX_KB',   5120),
        'formatos_imagen' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
    ],

    // ── Redes sociales ────────────────────────────────────
    'redes' => [
        'facebook'  => env('TIENDA_FACEBOOK',  ''),
        'instagram' => env('TIENDA_INSTAGRAM', ''),
        'tiktok'    => env('TIENDA_TIKTOK',    ''),
    ],

    // ── SEO ───────────────────────────────────────────────
    'seo' => [
        'titulo'      => env('TIENDA_SEO_TITULO',  ''),
        'descripcion' => env('TIENDA_SEO_DESC',    ''),
        'keywords'    => env('TIENDA_SEO_KEYWORDS',''),
    ],

];
