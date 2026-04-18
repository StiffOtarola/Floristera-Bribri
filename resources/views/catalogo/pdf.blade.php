<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        /* ══════════════════════════════════════════════════
           FLORISTERÍA BRIBRI — Catálogo PDF v3 (Boutique)
           Compatible DomPDF: sin gradients, sin ::before/after
           ══════════════════════════════════════════════════ */

        @page {
            margin: 1.2cm 1.5cm 1.8cm;
            size: A4 portrait;
            @bottom-center {
                content: "{{ config('floristeria.nombre') }}  •  {{ str_replace(['http://', 'https://'], '', config('app.url')) }}";
                font-family: Helvetica, Arial, sans-serif;
                font-size: 7pt;
                color: #aaaaaa;
            }
            @bottom-right {
                content: counter(page);
                font-family: Helvetica, Arial, sans-serif;
                font-size: 7pt;
                color: #aaaaaa;
            }
        }
        @page :first {
            @bottom-center { content: ""; }
            @bottom-right  { content: ""; }
        }

        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1C1C1C;
            font-size: 9.5pt;
            line-height: 1.5;
            background: #FFFFFF;
        }

        /* ════════════════════════════════════════════
           PORTADA — Estilo Boutique
           ════════════════════════════════════════════ */
        .cover-page {
            page-break-after: always;
            background: #1C3812;
            color: white;
            min-height: 780pt;
            text-align: center;
        }

        /* Bandas de acento arriba */
        .band-terracota { background: #C4714A; height: 8pt; }
        .band-verde     { background: #4A7A35; height: 3pt; }

        /* Marco decorativo interior */
        .cover-frame {
            margin: 14pt 22pt 14pt;
            border: 1pt solid rgba(255,255,255,0.14);
            padding: 28pt 32pt 22pt;
        }

        .cover-ornament {
            font-size: 10pt;
            letter-spacing: 10pt;
            color: rgba(255,255,255,0.18);
            margin-bottom: 20pt;
        }

        .cover-flowers-big {
            font-size: 46pt;
            margin-bottom: 6pt;
            line-height: 1.1;
        }

        .cover-flowers-row {
            font-size: 14pt;
            letter-spacing: 10pt;
            color: rgba(255,255,255,0.4);
            margin-bottom: 20pt;
        }

        .cover-eyebrow {
            font-size: 7pt;
            letter-spacing: 5pt;
            text-transform: uppercase;
            color: #C4714A;
            margin-bottom: 8pt;
        }

        .cover-store-name {
            font-size: 30pt;
            font-weight: 300;
            letter-spacing: 3pt;
            color: #FFFFFF;
            line-height: 1.15;
            margin-bottom: 2pt;
        }
        .cover-store-name strong {
            font-weight: 800;
            color: #E8B4A0;
            font-size: 38pt;
            letter-spacing: 2pt;
        }

        .cover-tagline {
            font-size: 9.5pt;
            color: rgba(255,255,255,0.48);
            font-weight: 300;
            letter-spacing: 0.5pt;
            margin-top: 6pt;
            margin-bottom: 16pt;
        }

        .cover-divider {
            width: 55pt;
            height: 1pt;
            background: #C4714A;
            margin: 0 auto 16pt;
        }

        .cover-catalog-tag {
            display: inline-block;
            border: 0.8pt solid rgba(255,255,255,0.18);
            padding: 6pt 22pt;
            font-size: 7.5pt;
            letter-spacing: 2pt;
            text-transform: uppercase;
            color: rgba(255,255,255,0.65);
            margin-bottom: 24pt;
        }

        /* Stats en portada */
        .cover-stats { margin-bottom: 20pt; }
        .cover-stats table { width: 68%; margin: 0 auto; border-collapse: collapse; }
        .cover-stats td { padding: 6pt 14pt; text-align: center; vertical-align: top; }
        .cs-num {
            font-size: 26pt;
            font-weight: 700;
            color: #E8B4A0;
            line-height: 1;
            display: block;
        }
        .cs-lbl {
            font-size: 6pt;
            text-transform: uppercase;
            letter-spacing: 1.5pt;
            color: rgba(255,255,255,0.35);
            margin-top: 3pt;
            display: block;
        }
        .cs-sep { color: rgba(255,255,255,0.08); font-size: 22pt; vertical-align: middle; }

        /* Contacto en portada */
        .cover-contact { border-top: 0.5pt solid rgba(255,255,255,0.1); padding-top: 14pt; margin-bottom: 16pt; }
        .cover-contact table { width: 90%; margin: 0 auto; border-collapse: collapse; }
        .cover-contact td { padding: 3pt 10pt; text-align: center; font-size: 8pt; color: rgba(255,255,255,0.42); }
        .cc-icon { font-size: 11pt; display: block; margin-bottom: 2pt; }

        .cover-web {
            font-size: 7.5pt;
            color: rgba(255,255,255,0.22);
            letter-spacing: 1pt;
            margin-bottom: 4pt;
        }
        .cover-date-txt {
            font-size: 7pt;
            color: rgba(255,255,255,0.18);
            font-style: italic;
        }
        .cover-ornament-bot {
            font-size: 10pt;
            letter-spacing: 10pt;
            color: rgba(255,255,255,0.12);
            margin-top: 16pt;
        }

        /* ════════════════════════════════════════════
           ÍNDICE DE CATEGORÍAS
           ════════════════════════════════════════════ */
        .toc-page {
            page-break-after: always;
            padding: 22pt 8pt;
        }

        .toc-header {
            text-align: center;
            margin-bottom: 22pt;
            padding-bottom: 14pt;
            border-bottom: 0.8pt solid #E8EAE0;
        }
        .toc-header-eyebrow {
            font-size: 7pt;
            letter-spacing: 4pt;
            text-transform: uppercase;
            color: #C4714A;
            margin-bottom: 5pt;
        }
        .toc-header-title {
            font-size: 22pt;
            font-weight: 300;
            color: #2A4A1E;
            letter-spacing: 1pt;
        }
        .toc-header-sub {
            font-size: 8pt;
            color: #bbb;
            margin-top: 4pt;
        }

        /* Filas del índice */
        .toc-table { width: 100%; border-collapse: collapse; }
        .toc-table tr { border-bottom: 0.5pt solid #F4F1EB; }
        .toc-table td { padding: 10pt 8pt; vertical-align: middle; }

        .toc-circle {
            width: 24pt;
            height: 24pt;
            background: #2A4A1E;
            color: white;
            border-radius: 50%;
            font-size: 7.5pt;
            font-weight: 700;
            text-align: center;
            line-height: 24pt;
            display: inline-block;
        }
        .toc-circle-col { width: 30pt; text-align: center; }

        .toc-cat-name { font-size: 12pt; color: #2A4A1E; font-weight: 600; }
        .toc-cat-desc { font-size: 7.5pt; color: #bbb; margin-top: 2pt; }

        .toc-badge {
            display: inline-block;
            background: #F8F5EE;
            color: #C4714A;
            font-size: 8pt;
            font-weight: 700;
            padding: 3pt 11pt;
            border-radius: 20pt;
        }
        .toc-count-col { width: 75pt; text-align: right; }

        /* Resumen TOC */
        .toc-summary {
            margin-top: 22pt;
            background: #2A4A1E;
            color: white;
            padding: 16pt 20pt;
            border-radius: 8pt;
        }
        .toc-summary table { width: 100%; border-collapse: collapse; }
        .toc-summary td { padding: 5pt 10pt; text-align: center; vertical-align: top; }
        .ts-num {
            font-size: 24pt;
            font-weight: 700;
            color: #E8B4A0;
            line-height: 1;
            display: block;
        }
        .ts-lbl {
            font-size: 6.5pt;
            text-transform: uppercase;
            letter-spacing: 1pt;
            color: rgba(255,255,255,0.45);
            margin-top: 3pt;
            display: block;
        }
        .ts-sep { color: rgba(255,255,255,0.1); font-size: 22pt; vertical-align: middle; }

        /* ════════════════════════════════════════════
           ENCABEZADO DE CATEGORÍA
           ════════════════════════════════════════════ */
        .cat-section { margin-bottom: 18pt; }

        .cat-header { margin-bottom: 12pt; }
        .cat-header-inner {
            border-left: 4pt solid #C4714A;
            padding-left: 14pt;
        }
        .cat-eyebrow {
            font-size: 6.5pt;
            letter-spacing: 3pt;
            text-transform: uppercase;
            color: #C4714A;
            margin-bottom: 2pt;
        }
        .cat-name {
            font-size: 19pt;
            font-weight: 300;
            color: #2A4A1E;
            line-height: 1.2;
        }
        .cat-name strong { font-weight: 800; }
        .cat-desc { font-size: 8pt; color: #aaa; font-style: italic; margin-top: 3pt; }
        .cat-meta { font-size: 7pt; color: #C4714A; margin-top: 4pt; }

        .cat-rule {
            border: none;
            border-top: 0.5pt solid #EAEAE4;
            margin: 10pt 0 12pt;
        }

        /* PDF bookmarks */
        .cat-bookmark {
            -dompdf-bookmark-label: attr(data-cat);
            -dompdf-bookmark-level: 1;
        }

        /* ════════════════════════════════════════════
           TARJETAS DE PRODUCTO — GRID 2 COLUMNAS
           ════════════════════════════════════════════ */
        .products-2col {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8pt 8pt;
        }
        .product-cell { width: 50%; vertical-align: top; }
        .product-cell-empty { width: 50%; }

        .p-card {
            background: #FAFAF6;
            border: 0.5pt solid #E4E8DF;
            border-radius: 8pt;
            page-break-inside: avoid;
        }

        /* Banda superior de color */
        .p-card-top {
            height: 4pt;
            background: #2A4A1E;
            border-radius: 8pt 8pt 0 0;
        }
        .p-card-top.featured { background: #C4714A; }

        /* Área de imagen */
        .p-card-img-wrap {
            background: #EDE8DE;
            text-align: center;
            padding: 13pt 8pt 11pt;
            border-bottom: 0.5pt solid #E4E8DF;
        }
        .p-card-img {
            width: 84pt;
            height: 72pt;
            object-fit: cover;
            border-radius: 5pt;
        }
        .p-card-img-ph {
            font-size: 34pt;
            line-height: 72pt;
        }

        /* Cuerpo de la tarjeta */
        .p-card-body { padding: 11pt 13pt 13pt; }

        /* Badges */
        .p-badge {
            display: inline-block;
            font-size: 5.5pt;
            padding: 1.5pt 7pt;
            border-radius: 20pt;
            text-transform: uppercase;
            letter-spacing: 0.8pt;
            margin-bottom: 5pt;
            margin-right: 3pt;
        }
        .p-badge-dest { background: #C4714A; color: white; }
        .p-badge-new  { background: #4A7A35; color: white; }

        .p-card-name {
            font-size: 10.5pt;
            font-weight: 700;
            color: #2A4A1E;
            line-height: 1.3;
            margin-bottom: 4pt;
        }
        .p-card-desc {
            font-size: 7.5pt;
            color: #888;
            line-height: 1.55;
            margin-bottom: 7pt;
        }
        .p-card-cat {
            display: inline-block;
            font-size: 6pt;
            color: #C4714A;
            text-transform: uppercase;
            letter-spacing: 1pt;
            border: 0.5pt solid rgba(196,113,74,0.35);
            padding: 1.5pt 7pt;
            border-radius: 20pt;
        }

        /* Footer precio + stock */
        .p-card-footer {
            border-top: 0.5pt solid #EAEAE4;
            padding-top: 8pt;
            margin-top: 8pt;
        }
        .p-foot-tbl { width: 100%; border-collapse: collapse; }
        .p-foot-tbl td { padding: 0; vertical-align: middle; }
        .p-foot-right { text-align: right; }

        .p-price-lbl { font-size: 6pt; color: #bbb; text-transform: uppercase; letter-spacing: 0.5pt; margin-bottom: 1pt; }
        .p-price {
            font-size: 15pt;
            font-weight: 800;
            color: #2A4A1E;
            line-height: 1;
        }
        .p-price-sym { font-size: 9pt; font-weight: 400; }

        .p-stock {
            display: inline-block;
            font-size: 6.5pt;
            padding: 2pt 8pt;
            border-radius: 20pt;
        }
        .stock-ok  { background: #E8F3E5; color: #3a7a26; }
        .stock-low { background: #FBF0E8; color: #C4714A; }
        .stock-out { background: #FDEAEA; color: #c0392b; }

        .p-sku { font-size: 6pt; color: #ccc; margin-top: 5pt; }

        /* ════════════════════════════════════════════
           SEPARADOR DE SECCIÓN
           ════════════════════════════════════════════ */
        .section-sep {
            text-align: center;
            margin: 18pt 0;
            color: #D8D2C8;
            font-size: 9pt;
            letter-spacing: 10pt;
        }

        /* ════════════════════════════════════════════
           PÁGINA DE CONTACTO
           ════════════════════════════════════════════ */
        .contact-page { page-break-before: always; }

        /* Hero */
        .contact-hero { background: #2A4A1E; color: white; margin-bottom: 18pt; }
        .contact-hero-band { background: #C4714A; height: 5pt; }
        .contact-hero-body { padding: 24pt 36pt 26pt; text-align: center; }

        .ch-eyebrow {
            font-size: 7pt;
            letter-spacing: 4pt;
            text-transform: uppercase;
            color: #C4714A;
            margin-bottom: 8pt;
        }
        .ch-title {
            font-size: 25pt;
            font-weight: 300;
            line-height: 1.2;
            margin-bottom: 6pt;
        }
        .ch-title strong { color: #E8B4A0; font-weight: 800; }
        .ch-sub { font-size: 9pt; color: rgba(255,255,255,0.5); font-weight: 300; }

        /* Tarjetas de contacto */
        .contact-cards-tbl {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8pt 0;
            margin-bottom: 16pt;
        }
        .contact-card {
            width: 33.33%;
            background: #F8F5EE;
            padding: 16pt 10pt;
            text-align: center;
            vertical-align: top;
            border-radius: 8pt;
            border-top: 3pt solid #2A4A1E;
        }
        .contact-card.primary { border-top-color: #C4714A; }
        .cc-icon-big { font-size: 20pt; display: block; margin-bottom: 6pt; }
        .cc-lbl {
            font-size: 6.5pt;
            text-transform: uppercase;
            letter-spacing: 1.5pt;
            color: #bbb;
            margin-bottom: 4pt;
        }
        .cc-val { font-size: 9pt; font-weight: 700; color: #2A4A1E; line-height: 1.4; }
        .cc-sub { font-size: 7.5pt; color: #aaa; margin-top: 3pt; }

        /* Botones CTA */
        .cta-row { text-align: center; margin: 14pt 0 16pt; }
        .btn-wa {
            display: inline-block;
            background: #25D366;
            color: white;
            padding: 10pt 26pt;
            border-radius: 50pt;
            font-size: 9.5pt;
            font-weight: 700;
            margin: 0 5pt;
        }
        .btn-store {
            display: inline-block;
            background: #2A4A1E;
            color: white;
            padding: 10pt 26pt;
            border-radius: 50pt;
            font-size: 9.5pt;
            font-weight: 700;
            margin: 0 5pt;
        }

        /* Tabla de info */
        .info-box {
            border: 0.5pt solid #E4E8DF;
            border-radius: 8pt;
            margin-bottom: 18pt;
        }
        .info-tbl { width: 100%; border-collapse: collapse; }
        .info-tbl td {
            padding: 8pt 14pt;
            font-size: 8.5pt;
            vertical-align: middle;
            border-bottom: 0.5pt solid #F4F2EE;
        }
        .info-tbl tr:last-child td { border-bottom: none; }
        .it-icon { font-size: 12pt; width: 28pt; text-align: center; }
        .it-lbl { width: 78pt; font-size: 7.5pt; color: #bbb; text-transform: uppercase; letter-spacing: 0.8pt; }
        .it-val { color: #2A4A1E; font-weight: 600; }

        /* Nota final */
        .final-note {
            text-align: center;
            padding: 14pt 10pt 10pt;
            border-top: 0.5pt solid #EEE9E0;
        }
        .fn-flowers { font-size: 14pt; letter-spacing: 8pt; margin-bottom: 8pt; }
        .fn-quote { font-size: 9.5pt; font-style: italic; font-weight: 300; color: #2A4A1E; margin-bottom: 9pt; }
        .fn-copy { font-size: 7pt; color: #ccc; }

        /* Helpers */
        .page-break { page-break-before: always; }
        a { color: inherit; text-decoration: none; }
        a.link-wa  { color: #2A4A1E; font-weight: 700; text-decoration: underline; }
        a.link-web { color: #C4714A; text-decoration: underline; }
        a.toc-link { color: #2A4A1E; text-decoration: none; display: block; }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════════
     PORTADA BOUTIQUE
     ═══════════════════════════════════════ --}}
<div class="cover-page">
    <div class="band-terracota"></div>
    <div class="band-verde"></div>

    <div class="cover-frame">
        <div class="cover-ornament">&#x2726; &nbsp;&nbsp;&nbsp; &#x2726; &nbsp;&nbsp;&nbsp; &#x2726;</div>

        <div class="cover-flowers-big">&#x1F33A;</div>
        <div class="cover-flowers-row">&#x1F33B; &nbsp; &#x1F337; &nbsp; &#x1F338;</div>

        <div class="cover-eyebrow">Floristeria &nbsp;&bull;&nbsp; Costa Rica &nbsp;&bull;&nbsp; Talamanca</div>

        <div class="cover-store-name">
            Floristeria<br><strong>Bribri</strong>
        </div>

        <div class="cover-tagline">Flores frescas con amor, desde Bribri</div>

        <div class="cover-divider"></div>

        <div class="cover-catalog-tag">
            Cat&aacute;logo {{ date('Y') }} &nbsp;&#x2022;&nbsp; {{ $totalProductos }} Productos
        </div>

        <div class="cover-stats">
            <table>
                <tr>
                    <td>
                        <span class="cs-num">{{ $categorias->count() }}</span>
                        <span class="cs-lbl">Categor&iacute;as</span>
                    </td>
                    <td class="cs-sep">|</td>
                    <td>
                        <span class="cs-num">{{ $totalProductos }}</span>
                        <span class="cs-lbl">Productos</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="cover-contact">
            <table>
                <tr>
                    <td>
                        <span class="cc-icon">&#x1F4F1;</span>
                        +{{ config('floristeria.whatsapp') }}
                    </td>
                    <td>
                        <span class="cc-icon">&#x1F4CD;</span>
                        {{ config('floristeria.direccion') }}
                    </td>
                    <td>
                        <span class="cc-icon">&#x1F552;</span>
                        L&ndash;S: 8am &ndash; 6pm
                    </td>
                </tr>
            </table>
        </div>

        <div class="cover-web">{{ str_replace(['http://', 'https://'], '', config('app.url')) }}</div>
        <div class="cover-date-txt">Actualizado &bull; {{ now()->translatedFormat('d \d\e F, Y') }}</div>

        <div class="cover-ornament-bot">&#x2726; &nbsp;&nbsp;&nbsp; &#x2726; &nbsp;&nbsp;&nbsp; &#x2726;</div>
    </div>

    <div class="band-verde"></div>
    <div class="band-terracota"></div>
</div>


{{-- ═══════════════════════════════════════
     ÍNDICE DE CATEGORÍAS
     ═══════════════════════════════════════ --}}
@php
    $icons = ['&#x1F339;','&#x1F490;','&#x1FAB4;','&#x1F48D;','&#x1F338;','&#x1F33A;','&#x1F33B;','&#x1F337;'];
    $totalStock = 0;
    $totalDestacados = 0;
    foreach($categorias as $cat) {
        foreach($cat->productos as $p) {
            $totalStock += $p->stock;
            if($p->destacado) $totalDestacados++;
        }
    }
@endphp

<div class="toc-page">
    <div class="toc-header">
        <div class="toc-header-eyebrow">Cat&aacute;logo {{ date('Y') }}</div>
        <div class="toc-header-title">&Iacute;ndice de Categor&iacute;as</div>
        <div class="toc-header-sub">{{ $categorias->count() }} categor&iacute;as &nbsp;&bull;&nbsp; {{ $totalProductos }} productos disponibles</div>
    </div>

    <table class="toc-table">
        @foreach($categorias as $catIndex => $cat)
        <tr>
            <td class="toc-circle-col">
                <span class="toc-circle">{{ str_pad($catIndex + 1, 2, '0', STR_PAD_LEFT) }}</span>
            </td>
            <td style="padding-left:6pt;">
                <a class="toc-link" href="#cat-{{ $cat->id }}">
                    <div class="toc-cat-name">{!! $icons[$catIndex % count($icons)] !!} &nbsp; {{ $cat->nombre }}</div>
                    @if($cat->descripcion)
                        <div class="toc-cat-desc">{{ $cat->descripcion }}</div>
                    @endif
                </a>
            </td>
            <td class="toc-count-col">
                <a class="toc-link" href="#cat-{{ $cat->id }}" style="text-align:right; display:block;">
                    <span class="toc-badge">{{ $cat->productos->count() }} prod.</span>
                </a>
            </td>
        </tr>
        @endforeach
    </table>

    <div class="toc-summary">
        <table>
            <tr>
                <td>
                    <span class="ts-num">{{ $categorias->count() }}</span>
                    <span class="ts-lbl">Categor&iacute;as</span>
                </td>
                <td class="ts-sep">|</td>
                <td>
                    <span class="ts-num">{{ $totalProductos }}</span>
                    <span class="ts-lbl">Productos</span>
                </td>
                <td class="ts-sep">|</td>
                <td>
                    <span class="ts-num">{{ $totalDestacados }}</span>
                    <span class="ts-lbl">Destacados</span>
                </td>
                <td class="ts-sep">|</td>
                <td>
                    <span class="ts-num">{{ $totalStock }}</span>
                    <span class="ts-lbl">En stock</span>
                </td>
            </tr>
        </table>
    </div>
</div>


{{-- ═══════════════════════════════════════
     CATÁLOGO — GRID DE 2 COLUMNAS
     ═══════════════════════════════════════ --}}
@foreach($categorias as $catIndex => $cat)
    @if($catIndex > 0 && $catIndex % 2 === 0)
        <div class="page-break"></div>
    @endif

    <div class="cat-section">
        <a name="cat-{{ $cat->id }}"></a>

        {{-- Encabezado de categoría con bookmark para panel PDF --}}
        <div class="cat-header cat-bookmark" data-cat="{{ $cat->nombre }}">
            <div class="cat-header-inner">
                <div class="cat-eyebrow">Categor&iacute;a {{ str_pad($catIndex + 1, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="cat-name">
                    {!! $icons[$catIndex % count($icons)] !!} &nbsp;<strong>{{ $cat->nombre }}</strong>
                </div>
                @if($cat->descripcion)
                    <div class="cat-desc">{{ $cat->descripcion }}</div>
                @endif
                <div class="cat-meta">{{ $cat->productos->count() }} producto{{ $cat->productos->count() !== 1 ? 's' : '' }} disponibles</div>
            </div>
        </div>
        <hr class="cat-rule">

        {{-- Grid 2 columnas --}}
        <table class="products-2col">
            @foreach($cat->productos->chunk(2) as $pair)
            <tr>
                @foreach($pair as $p)
                @php
                    $esNuevo  = $p->created_at && \Carbon\Carbon::parse($p->created_at)->diffInDays(now()) <= 30;
                    $imgPath  = public_path('storage/' . $p->imagen);
                    $tieneImg = !empty($p->imagen) && file_exists($imgPath);
                @endphp
                <td class="product-cell">
                    <div class="p-card">
                        <div class="p-card-top {{ $p->destacado ? 'featured' : '' }}"></div>

                        {{-- Imagen --}}
                        <div class="p-card-img-wrap">
                            @if($tieneImg)
                                <img class="p-card-img" src="{{ $imgPath }}" alt="{{ $p->nombre }}">
                            @else
                                <div class="p-card-img-ph">&#x1F33C;</div>
                            @endif
                        </div>

                        {{-- Cuerpo --}}
                        <div class="p-card-body">
                            <div>
                                @if($p->destacado)
                                    <span class="p-badge p-badge-dest">&#x2B50; Destacado</span>
                                @endif
                                @if($esNuevo)
                                    <span class="p-badge p-badge-new">&#x2728; Nuevo</span>
                                @endif
                            </div>

                            <div class="p-card-name">{{ $p->nombre }}</div>
                            <div class="p-card-desc">{{ Str::limit($p->descripcion ?: 'Producto de la más alta calidad.', 88) }}</div>
                            <div class="p-card-cat">{{ $cat->nombre }}</div>

                            <div class="p-card-footer">
                                <table class="p-foot-tbl">
                                    <tr>
                                        <td>
                                            <div class="p-price-lbl">Precio</div>
                                            <div class="p-price">
                                                <span class="p-price-sym">&#x20A1;</span>{{ number_format($p->precio, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="p-foot-right">
                                            @if($p->stock > 10)
                                                <span class="p-stock stock-ok">&#x2705; Disp.</span>
                                            @elseif($p->stock > 0)
                                                <span class="p-stock stock-low">&#x26A0; {{ $p->stock }} uds.</span>
                                            @else
                                                <span class="p-stock stock-out">&#x274C; Agotado</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                                <div class="p-sku"># {{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                    </div>
                </td>
                @endforeach

                @if($pair->count() == 1)
                <td class="product-cell-empty"></td>
                @endif
            </tr>
            @endforeach
        </table>
    </div>

    @if(!$loop->last)
        <div class="section-sep">&#x2726; &nbsp; &#x2726; &nbsp; &#x2726;</div>
    @endif
@endforeach


{{-- ═══════════════════════════════════════
     PÁGINA DE CONTACTO
     ═══════════════════════════════════════ --}}
<div class="contact-page">

    <div class="contact-hero">
        <div class="contact-hero-band"></div>
        <div class="contact-hero-body">
            <div class="ch-eyebrow">F l o r i s t e r &iacute; a &nbsp;&bull;&nbsp; B r i b r i</div>
            <div class="ch-title">
                &#x1F338; &iquest;Te gust&oacute; algo?<br><strong>&iexcl;Ped&iacute; ya!</strong>
            </div>
            <div class="ch-sub">Hac&eacute; tu pedido por WhatsApp, visit&aacute; nuestra tienda o compr&aacute; en la web</div>
        </div>
    </div>

    <table class="contact-cards-tbl">
        <tr>
            <td class="contact-card primary">
                <span class="cc-icon-big">&#x1F4F1;</span>
                <div class="cc-lbl">WhatsApp</div>
                <div class="cc-val">
                    <a class="link-wa" href="https://wa.me/{{ config('floristeria.whatsapp') }}?text=Hola%2C%20vi%20el%20cat%C3%A1logo%20y%20quiero%20hacer%20un%20pedido">
                        +{{ config('floristeria.whatsapp') }}
                    </a>
                </div>
                <div class="cc-sub">Toc&aacute; para chatear</div>
            </td>
            <td class="contact-card">
                <span class="cc-icon-big">&#x1F4CD;</span>
                <div class="cc-lbl">Ubicaci&oacute;n</div>
                <div class="cc-val">
                    <a href="{{ config('floristeria.maps_url') ?: 'https://maps.google.com/?q=' . urlencode(config('floristeria.direccion')) }}">
                        {{ config('floristeria.direccion') }}
                    </a>
                </div>
                <div class="cc-sub">Lim&oacute;n, Costa Rica</div>
            </td>
            <td class="contact-card">
                <span class="cc-icon-big">&#x1F4E7;</span>
                <div class="cc-lbl">Correo</div>
                <div class="cc-val" style="font-size:8pt;">
                    <a href="mailto:{{ config('floristeria.admin_email') }}">{{ config('floristeria.admin_email') }}</a>
                </div>
                <div class="cc-sub">Consultas generales</div>
            </td>
        </tr>
    </table>

    <div class="cta-row">
        <a class="btn-wa" href="https://wa.me/{{ config('floristeria.whatsapp') }}?text=Hola%2C%20vi%20el%20cat%C3%A1logo%20y%20quiero%20hacer%20un%20pedido">
            &#x1F4F1; &nbsp; Pedir por WhatsApp
        </a>
        <a class="btn-store" href="{{ config('app.url') }}">
            &#x1F310; &nbsp; Ver tienda online
        </a>
    </div>

    <div class="info-box">
        <table class="info-tbl">
            <tr>
                <td class="it-icon">&#x1F552;</td>
                <td class="it-lbl">Horario</td>
                <td class="it-val">Lun &ndash; S&aacute;b: 8:00 am &ndash; 6:00 pm &nbsp;&bull;&nbsp; Dom: 9:00 am &ndash; 2:00 pm</td>
            </tr>
            <tr>
                <td class="it-icon">&#x1F697;</td>
                <td class="it-lbl">Env&iacute;o</td>
                <td class="it-val">A domicilio: &#x20A1;{{ number_format(config('floristeria.costo_envio'), 0, ',', '.') }} &nbsp;&bull;&nbsp; Retiro en local: &iexcl;Gratis!</td>
            </tr>
            <tr>
                <td class="it-icon">&#x1F4B0;</td>
                <td class="it-lbl">Pago</td>
                <td class="it-val">Sinpe M&oacute;vil &nbsp;&bull;&nbsp; Transferencia &nbsp;&bull;&nbsp; Efectivo</td>
            </tr>
            <tr>
                <td class="it-icon">&#x2728;</td>
                <td class="it-lbl">Especial</td>
                <td class="it-val">Personalizamos arreglos para bodas, cumplea&ntilde;os y eventos especiales</td>
            </tr>
            <tr>
                <td class="it-icon">&#x1F310;</td>
                <td class="it-lbl">Web</td>
                <td class="it-val">
                    <a class="link-web" href="{{ config('app.url') }}">{{ str_replace(['http://', 'https://'], '', config('app.url')) }}</a>
                    &nbsp;&bull;&nbsp; Pedidos online 24/7
                </td>
            </tr>
        </table>
    </div>

    <div class="final-note">
        <div class="fn-flowers">&#x1F33A; &nbsp; &#x1F33B; &nbsp; &#x1F337;</div>
        <div class="fn-quote">&ldquo;Cada flor que entregamos lleva un pedacito de Bribri y mucho amor costarricense&rdquo;</div>
        <div class="fn-copy">
            &copy; {{ date('Y') }} {{ config('floristeria.nombre') }} &nbsp;&bull;&nbsp; Todos los derechos reservados<br>
            Cat&aacute;logo generado el {{ now()->translatedFormat('d/m/Y') }} &nbsp;&bull;&nbsp;
            <a class="link-web" href="{{ config('app.url') }}">{{ str_replace(['http://', 'https://'], '', config('app.url')) }}</a>
        </div>
    </div>

</div>

</body>
</html>
