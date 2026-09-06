<?php

namespace App\Core;

class LicenseGenerator
{
    public static function generateKey(): string
    {
        $part1 = strtoupper(bin2hex(random_bytes(2)));
        $part2 = strtoupper(bin2hex(random_bytes(2)));
        $part3 = strtoupper(bin2hex(random_bytes(2)));

        return "EAFD-{$part1}-{$part2}-{$part3}";
    }
}
