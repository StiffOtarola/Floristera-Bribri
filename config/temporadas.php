<?php

/*
|--------------------------------------------------------------------------
| Temporadas Florales
|--------------------------------------------------------------------------
| Cada entrada define un período especial donde el sitio cambia de tema.
| inicio/fin: [mes, dia] — el año se toma automáticamente del año actual.
| Los colores sobreescriben --terracota y --rosa del tema base.
|--------------------------------------------------------------------------
*/

return [

    [
        'nombre'        => 'San Valentín',
        'inicio'        => [2, 7],
        'fin'           => [2, 14],
        'flor'          => 'Rosas Rojas',
        'emoji'         => '🌹',
        'banner'        => 'San Valentín — Rosas rojas frescas para regalar amor',
        'banner_color'  => '#9B1B1B',
        'hero_titulo'   => 'Flores que <em>enamoran</em>',
        'hero_desc'     => 'San Valentín es el momento perfecto para expresar tu amor. Rosas rojas frescas, directas desde Bribri a su corazón.',
        'colores'       => [
            'terracota' => 'A93226',
            'rosa'      => 'FADBD8',
        ],
    ],

    [
        'nombre'        => 'Día de la Madre',
        'inicio'        => [8, 10],
        'fin'           => [8, 15],
        'flor'          => 'Lirios y Orquídeas',
        'emoji'         => '🌸',
        'banner'        => 'Día de la Madre — Honra a mamá con lirios y orquídeas',
        'banner_color'  => '#6C3483',
        'hero_titulo'   => 'Para la mujer más <em>especial</em>',
        'hero_desc'     => 'El Día de la Madre merece flores únicas. Lirios y orquídeas que expresan respeto, amor y gratitud eterna.',
        'colores'       => [
            'terracota' => '7D3C98',
            'rosa'      => 'EBDEF0',
        ],
    ],

    [
        'nombre'        => 'Primavera — Girasoles',
        'inicio'        => [3, 17],
        'fin'           => [3, 24],
        'flor'          => 'Girasoles',
        'emoji'         => '🌻',
        'banner'        => 'Bienvenida Primavera — Girasoles que iluminan tu día',
        'banner_color'  => '#B7770D',
        'hero_titulo'   => 'El sol florece <em>contigo</em>',
        'hero_desc'     => 'La primavera llega con girasoles radiantes. Un símbolo de amor eterno y alegría que nunca pasa de moda.',
        'colores'       => [
            'terracota' => 'C9940A',
            'rosa'      => 'FEF9E7',
        ],
    ],

    [
        'nombre'        => 'Otoño — Girasoles',
        'inicio'        => [9, 18],
        'fin'           => [9, 24],
        'flor'          => 'Girasoles',
        'emoji'         => '🌻',
        'banner'        => 'Equinoccio — Girasoles: "Quiero que te quedes"',
        'banner_color'  => '#B7770D',
        'hero_titulo'   => 'Flores que piden <em>quedarse</em>',
        'hero_desc'     => 'Los girasoles en otoño dicen lo que las palabras no pueden. Regala permanencia, regala amor eterno.',
        'colores'       => [
            'terracota' => 'C9940A',
            'rosa'      => 'FEF9E7',
        ],
    ],

    [
        'nombre'        => 'Flores Azules',
        'inicio'        => [9, 25],
        'fin'           => [10, 3],
        'flor'          => 'Flores Azules',
        'emoji'         => '💙',
        'banner'        => 'Temporada Azul — El regalo perfecto y diferente para él',
        'banner_color'  => '#1A5276',
        'hero_titulo'   => 'Un detalle <em>diferente</em>',
        'hero_desc'     => 'Las flores azules son el detalle más original para los hombres especiales de tu vida. Sorpréndelos esta temporada.',
        'colores'       => [
            'terracota' => '1F618D',
            'rosa'      => 'D6EAF8',
        ],
    ],

    [
        'nombre'        => 'Flores Moradas',
        'inicio'        => [10, 5],
        'fin'           => [10, 15],
        'flor'          => 'Flores Moradas',
        'emoji'         => '💜',
        'banner'        => 'Temporada Morada — Admiración y gratitud en cada pétalo',
        'banner_color'  => '#6C3483',
        'hero_titulo'   => 'Flores de <em>admiración</em>',
        'hero_desc'     => 'Las flores moradas expresan lo que guardas en el corazón: admiración, gratitud y un cariño profundo.',
        'colores'       => [
            'terracota' => '7D3C98',
            'rosa'      => 'E8DAEF',
        ],
    ],

];
