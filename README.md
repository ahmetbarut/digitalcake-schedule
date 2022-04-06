# Schedule

Paketin genel amacı mailler ve sms'ler için ileriki tarihler için zamanlamak, ama `mail tracking`'de kullanılıyor.

## Kurulum

Kurulum için öncelikle ilgili sunucuda bu repoya erişim yetkisinin olması gerekli.

```shell
    composer require digitalcake/scheduling
```

Paket kurulduktan sonra bazı dosyaların yayınlanması gerekli. İsterseniz `php artisan vendor:publish` yapıp bazılarını yayınlayabilirsiniz, isterseniz de hepsini tek seferde.

```shell
    # Hepsini yayınlayacak
    php artisan vendor:publish --provider=Digitalcake\Scheduling\Providers\ScheduleServiceProvider
```
