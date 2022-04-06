<?php

return [
    /**
     * Mail trace log
     */
    'smtp2go' => [
        /**
         * Mail sonuçlarının nereye geleceğini belirtir.
         */
        'webhook_url' => env('SMTP2GO_WEBHOOK_URL', 'https://api.smtp2go.com/v3/webhook/'),

        /**
         * Kullanıcıları hangi aksiyonlarda kaldırması gerektiğini belirtebilirsiniz.
         */
        'destroy_users' => [
            'bounced',
            'unsubscribed',
            'rejected',
            'spam',
        ],

        // 'resubscribe_users' => [
        //     'resubscribed',
        // ],
    ],

    /**
     * Birthday emails'in varsayılan gönderimi kapalıdır.
     * Bu ayarı açmak için 'birthday_email_enabled' => true, şeklinde yapın.
     * Bu ayarı açarsanız, model belirtmeniz gerekli ve
     * modelde Digitalcake\Scheduling\Contracts\UserBirthdayContract arayüzünü uygulaması gereklidir.
    */
    'birthday_email_enabled' => false,

    /**
     * Doğum günü maillerinin hangi modele bağlı olduğunu belirtir.
     * Burada model belirtmeniz durumunda, modelde Digitalcake\Scheduling\Contracts\UserBirthdayContract
     * arayüzünü uygulaması gereklidir. Arayüzü uygulamadığınız sürece bu ayar kullanılamaz.
     */
    'birthday_model' => null,
];
