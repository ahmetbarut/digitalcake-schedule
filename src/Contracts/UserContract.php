<?php

namespace Digitalcake\Scheduling\Contracts;

interface UserContract
{
    /**
     * Kullanıcı e-posta adresini geriye döndürür.
     * @return string
     */
    public function getEmail(): string;
}
