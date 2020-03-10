<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShopRepository")
 * @ORM\Table(name="shops")
 *
 */
class ShopEntity
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $externalId;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $pictureUrl;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $address;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $zipCode;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $city;
    
    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $bestReduction;
    
    /**
     * @param int    $externalId
     * @param string $name
     * @param string $address
     * @param string $zipCode
     * @param string $city
     * @param float  $bestReduction
     * @param string $pictureUrl
     */
    public function __construct(
        $externalId, $name, $address, $zipCode, $city, $bestReduction, $pictureUrl
    )
    {
        $this->externalId       = $externalId;
        $this->name             = $name;
        $this->address          = $address;
        $this->zipCode          = $zipCode;
        $this->city             = $city;
        $this->bestReduction    = $bestReduction;
        $this->pictureUrl       = $pictureUrl;
    }
    
    public function getExternalId()
    {
        return $this->externalId;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPictureUrl()
    {
        return $this->pictureUrl;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getZipCode()
    {
        return $this->zipCode;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getBestReduction()
    {
        return $this->bestReduction;
    }
    
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPictureUrl($pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function setBestReduction($bestReduction)
    {
        $this->bestReduction = $bestReduction;
    }
}