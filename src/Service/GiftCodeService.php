<?php

namespace D4rk0snet\GiftCode\Service;

class GiftCodeService
{
    public static function createGiftCode(string $email): string
    {
        return substr(md5($email . random_int(0, PHP_INT_MAX)), 0, 8);
    }

    public static function exportAsTxt(array $labels, array $items, string $delimiter, string $filename): void
    {
        $stringLabel = "";

        foreach ($labels as $label) {
            $stringLabel .= $label;
            $stringLabel .= PHP_EOL;
        }

        foreach ($items as $item) {
            $stringLabel .= $item;
            $stringLabel .= PHP_EOL;
        }

        $fh = fopen($filename, 'w');

        $stringLabel = trim($stringLabel);
        fwrite($fh, $stringLabel);
        fclose($fh);
    }
}