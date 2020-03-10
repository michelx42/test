<?php

namespace App\Builder;

use App\Entity\ShopEntity;

/**
 * Builder for shop entities
 */
class ShopBuilder
{
    static public function build(array $shopsData): array
    {
        $entities = [];
        foreach ($shopsData as $shop) {
            $entities[] = new ShopEntity(
                $shop['externalId'],
                $shop['name'],
                $shop['address'],
                $shop['zipCode'],
                $shop['city'],
                $shop['bestReduction'],
                $shop['pictureUrl']    
            );
        }
        
        return $entities;
    }
}