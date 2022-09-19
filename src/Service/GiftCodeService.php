<?php

namespace D4rk0snet\GiftCode\Service;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Enums\AdoptedProduct;

class GiftCodeService
{
    public static function createGiftCode(string $seed, AdoptedProduct $adoptedProduct): string
    {
        $base = $adoptedProduct === AdoptedProduct::CORAL ? "CORAL" : "REEF";
        return $base . substr(md5($seed . random_int(0, PHP_INT_MAX)), 0, 8);
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