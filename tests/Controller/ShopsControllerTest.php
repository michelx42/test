<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use App\Controller\ShopsController;
use App\DataLoader\ShopsDataLoader;
use App\Parser\ChainParser;
use App\Repository\ShopRepository;

class ShopsControllerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $loaderMock;
    
    /**
     * @var MockObject
     */
    private $parserMock;
    
    /**
     * @var MockObject
     */
    private $repositoryMock;
    
    /**
     * @var MockObject
     */
    private $entityManagerMock;
    
    /**
     * @var ShopsController
     */
    private $controller;    
    
    public function setUp()
    {
        $this->loaderMock = $this->getMockBuilder(ShopsDataLoader::class)->getMock();
        $this->parserMock = $this->getMockBuilder(ChainParser::class)->getMock();
        $this->repositoryMock = $this->getMockBuilder(ShopRepository::class)
                ->disableOriginalConstructor()
                ->getMock();
 
        $this->loaderMock->expects($this->once())->method('load')->willReturn([['chain1']]);
        $shopData = [
            'externalId' => 1,
            'name' => 'shop',
            'address' => 'address',
            'zipCode' => 'zipCode',
            'city' => 'city',
            'bestReduction' => 10.0,
            'pictureUrl' => 'url'
        ];
        $this->parserMock->expects($this->once())
                ->method('parse')
                ->with(['chain1'])
                ->willReturn([$shopData]);
        
        $doctrineRegistryMock = $this->getMockBuilder(Registry::class)
                ->disableOriginalConstructor()
                ->getMock();
        $this->entityManagerMock = $this->getMockBuilder(EntityManager::class)
                ->disableOriginalConstructor()
                ->getMock();
        $doctrineRegistryMock->expects($this->once())
                ->method('getManager')
                ->willReturn($this->entityManagerMock);
        
        $this->controller = $this->getMockBuilder(ShopsController::class)
                ->setMethods(['getDoctrine'])
                ->getMock();
        $this->controller->expects($this->once())->method('getDoctrine')->willReturn($doctrineRegistryMock);
    }
    
    public function testPullSavesShopWhenNotFoundInDatabase()
    {
        $this->repositoryMock->expects($this->once())->method('findBy')->willReturn([]);
        $this->entityManagerMock->expects($this->once())->method('persist');
        $this->entityManagerMock->expects($this->once())->method('flush');

        $response = $this->controller->pull($this->loaderMock, $this->parserMock, $this->repositoryMock);
        $this->assertInstanceOf(Response::class, $response);
    }
    
    public function testPullDoesNotSaveShopWhenFoundInDatabase()
    {
        $this->repositoryMock->expects($this->once())->method('findBy')->willReturn(['exists']);
        $this->entityManagerMock->expects($this->never())->method('persist');
        $this->entityManagerMock->expects($this->once())->method('flush');

        $response = $this->controller->pull($this->loaderMock, $this->parserMock, $this->repositoryMock);
        $this->assertInstanceOf(Response::class, $response);
    }
}