<?php

namespace Digitalcake\Scheduling\Contracts;

interface UserBirthdayContract extends UserContract
{
    /**
     * Kullanıcı doğum gününün tutulduğu kolonu getirir.
     */
    public function getBirthdateColumn(): string;
}
