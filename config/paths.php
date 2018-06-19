<?php

$app_domain = 'http://localhost:8080';

return [
    'site' => [
        'paths' => [
            'news_images' => $app_domain . '/images/vijesti/galerija/',
            'player_images' =>$app_domain . '/images/athlete_avatars/',
            'player_gallery' => $app_domain . '/images/galerija_sportista/'
        ],
        'defaults' => [
            'news_default_image' => $app_domain . '/images/vijesti/vijesti-dodaj-sliku.png'
        ]
    ]
];
