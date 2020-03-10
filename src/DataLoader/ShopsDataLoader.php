<?php

namespace App\DataLoader;

/**
 * Loader to pull data from url
 */
class ShopsDataLoader
{
    public function load(): array
    {
        $json = file_get_contents('https://www.leshabitues.fr/testapi/shops');
        if (empty($json) === true) {
            throw new \Exception('Unable to pull data from url');
        }
        $obj = json_decode($json, true);

        return $obj['data'];
    }
}