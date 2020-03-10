<?php

namespace App\Tests\Builder;

use PHPUnit\Framework\TestCase;
use App\Builder\ShopBuilder;

class ShopBuilderTest extends TestCase
{
    public function testBuildTwoEntitiesFromArray()
    {
        $shopsData = [
            [
                'externalId' => 1,
                'name' => 'shop1',
                'address' => 'address1',
                'zipCode' => 'zipCode1',
                'city' => 'city1',
                'bestReduction' => 10.1,
                'pictureUrl' => 'url1'
            ],
            [
                'externalId' => 2,
                'name' => 'shop2',
                'address' => 'address2',
                'zipCode' => 'zipCode2',
                'city' => 'city2',
                'bestReduction' => 10.2,
                'pictureUrl' => 'url2'
            ]
        ];
        
        $entities = ShopBuilder::build($shopsData);
        $this->assertCount(2, $entities);

        foreach ($entities as $id => $entity) {
            $this->assertEquals($shopsData[$id]['externalId'], $entity->getExternalId());
            $this->assertEquals($shopsData[$id]['name'], $entity->getName());
            $this->assertEquals($shopsData[$id]['address'], $entity->getAddress());
            $this->assertEquals($shopsData[$id]['zipCode'], $entity->getZipCode());
            $this->assertEquals($shopsData[$id]['city'], $entity->getCity());
            $this->assertEquals($shopsData[$id]['bestReduction'], $entity->getBestReduction());
            $this->assertEquals($shopsData[$id]['pictureUrl'], $entity->getPictureUrl());
        }
    }
}