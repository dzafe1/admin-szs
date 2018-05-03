<?php

$domain = 'http://localhost:8080';
$images = 'D:\xampp\htdocs\szs\public\images';

return [
    'site_paths' => [
        'display_news' => $domain . '/news/',
        'news_images' => $domain . '/images/vijesti/galerija/'
    ],
    'image_paths' => [
        'vijesti' => $images . '\vijesti\galerija\\'
    ]
];