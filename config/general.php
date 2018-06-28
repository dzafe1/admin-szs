<?php

$domain = 'http://localhost:8080';
$images = 'D:\xampp\htdocs\szs\public\images';

return [
    'site_paths' => [
        'display_news' => $domain . '/news/',
        'news_images' => $domain . '/images/vijesti/galerija/',
        'player_images' => $domain . '/images/athlete_avatars/',
        'player_gallery' => $domain . '/images/galerija_sportista/',
        'object_images' => $domain . '/images/object_avatars/',
        'object_gallery' => $domain . '/images/galerija_objekti/',
        'club_images' => $domain . '/images/club_logo/',
        'club_gallery' => $domain . '/images/galerija_klub/',
        'licnost_images' => $domain . '/images/avatar_licnost/',
        'school_images' => $domain . '/images/school_logo/',
        'school_gallery' => $domain . '/images/galerija_skola/',
        'staff_images' => $domain . '/images/staff_avatars/',
        'staff_gallery' => $domain . '/images/galerija_kadrovi/',
        'association_images' => $domain . '/images/association_logo/'
    ],
    'image_paths' => [
        'vijesti' => $images . '\vijesti\galerija\\',
        'player_images' => $images . '\athlete_avatars\\',
        'player_gallery' => $images . '\galerija_sportista\\',
        'object_images' => $images . '\object_avatars\\',
        'object_gallery' => $images . '\galerija_objekti\\',
        'club_images' => $images . '\club_logo\\',
        'club_gallery' => $images . '\galerija_klub\\',
        'licnost_images' => $images . '\avatar_licnost\\',
        'school_images' => $images . '\school_logo\\',
        'school_gallery' => $images . '\galerija_skola\\',
        'staff_images' => $images . '\staff_avatars\\',
        'staff_gallery' => $images . '\galerija_kadrovi\\',
        'association_images' => $images . '\association_logo\\'
    ]
];