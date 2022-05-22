<?php

namespace D4rk0snet\GiftCode\Service;

class GiftCodeService
{
    public static function createGiftCode(string $email)
    {
        return substr(md5($email.random_int(0,PHP_INT_MAX)),0,8);
    }
}