<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Navigations
    |--------------------------------------------------------------------------
    |
    | Eğer bir paket kullanılıyorsa buradan o pakete ait
    | menüler(navigations)'in eklenmesi gereklidir
    | Buraya hangi paketlerin menüsü mevcutsa onlar eklenecek.
    |--------------------------------------------------------------------------
    | Navigation sınıfları `app/Contracts/Navigation.php`
    | arayüzünü uygulamak zorundadır.
    */
    'navigations' => [
        Digitalcake\Scheduling\Navigation\Navigation::class,
    ],
];
