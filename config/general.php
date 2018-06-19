<?php

$domain = 'http://localhost:8080';
$images = 'D:\xampp\htdocs\szs\public\images';

return [
    'site_paths' => [
        'display_news' => $domain . '/news/',
        'news_images' => $domain . '/images/vijesti/galerija/',
        'player_images' => $domain . '/images/athlete_avatars/',
        'player_gallery' => $domain . '/images/galerija_sportista/'
    ],
    'image_paths' => [
        'vijesti' => $images . '\vijesti\galerija\\',
        'player_images' => $images . '\athlete_avatars\\',
        'player_gallery' => $images . '\galerija_sportista\\'
    ]
];