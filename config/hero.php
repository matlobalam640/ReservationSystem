<?php

return [
    'branding' => [
        'logo' => 'images/hero/logo-white.png',
        'hero_banner' => 'images/hero/hero-banner.jpg',
        'hero_mission' => 'images/hero/hero-mission.jpg',
        'medevac' => 'images/hero/medevac-helicopter.jpg',
        'favicon' => 'images/hero/favicon.png',
        'source_url' => 'https://www.heroclientrescue.com/',
    ],

    'locations' => [
        'PAP' => 'Pétion-Ville',
        'PVC' => 'Pétion-Ville',
        'SDQ' => 'Santo Domingo',
        'CAP' => 'Cap-Haïtien',
    ],

    'contact' => [
        'address' => '115 Ave. Panamericaine, Hotel Oasis, Suite 302, Pétion-Ville, Haiti, HT6140',
        'website' => 'https://www.heroclientrescue.com/',
        'phone_hero' => '+509 3333 4376',
        'phone_helo' => '+509 3333 4356',
        'chat_label' => 'Chat with us',
    ],

    'featured_routes' => [
        [
            'badge' => 'New destination!',
            'origin' => 'PAP',
            'destination' => 'SDQ',
            'title' => 'Pétion-Ville ⇄ Santo Domingo',
            'from_price' => 575.00,
            'description' => 'Cross-border shuttle service between Haiti and the Dominican Republic.',
        ],
        [
            'badge' => null,
            'origin' => 'PAP',
            'destination' => 'CAP',
            'title' => 'Cap-Haïtien',
            'from_price' => 600.00,
            'description' => 'Regular connections to Cap-Haïtien and destinations across Haiti.',
        ],
    ],
];
