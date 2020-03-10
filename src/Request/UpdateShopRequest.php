<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\ShopEntity;

class UpdateShopRequest
{

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="100")
     * @var string
     */
    public $externalId;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="100")
     * @var string
     */
    public $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="100")
     * @var string
     */
    public $address;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="100")
     * @var string
     */
    public $zipCode;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="100")
     * @var string
     */
    public $city;

    public static function fromShop(ShopEntity $shop): self
    {
        $updateShopRequest = new self();
        $updateShopRequest->externalId = $shop->getExternalId();
        $updateShopRequest->name = $shop->getName();
        $updateShopRequest->address = $shop->getAddress();
        $updateShopRequest->zipCode = $shop->getZipCode();
        $updateShopRequest->city = $shop->getCity();

        return $updateShopRequest;
    }
}