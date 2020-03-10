<?php

namespace App\Parser;

/**
 * Parse shops data from chain raw data
 */
class ChainParser
{
    public function parse(array $chain): array
    {
        $reductions = [];
        foreach ($chain['offers'] as $offer) {
            $reductions[] = $offer['reduction'];
        }
        
        $shops = [];
        foreach ($chain['localisations'] as $shop) {
            $shops[] = [
                'externalId'    => $shop['id'],
                'name'          => $shop['name'],
                'address'       => $shop['address'],
                'zipCode'       => $shop['zipcode'],
                'city'          => $shop['city'],
                'bestReduction' => max($reductions),
                'pictureUrl'    => $chain['picture_url']
            ];
        }
        
        return $shops;
    }
}