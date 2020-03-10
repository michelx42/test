<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\Common\Util\Debug;
use App\DataLoader\ShopsDataLoader;
use App\Parser\ChainParser;
use App\Repository\ShopRepository;
use App\Builder\ShopBuilder;
use App\Form\ShopType;
use App\Request\UpdateShopRequest;

/**
 * Controller used to manage shops
 */
class ShopsController extends AbstractController
{
    
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, ShopRepository $repository): Response
    {
        $forms = [];
        $shops = $this->listShops($repository);
        foreach ($shops as $shop) {
            $updateShopRequest = UpdateShopRequest::fromShop($shop);
            $form = $this->createForm(ShopType::class, $updateShopRequest);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $formData = $form->getData();
                if ($shop->getExternalId() == $formData->externalId) {
                    $shop->setName($formData->name);
                    $shop->setAddress($formData->address);
                    $shop->setZipCode($formData->zipCode);
                    $shop->setCity($formData->city);
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();    
                    return new RedirectResponse('/');
                }
            }
            
            $forms[] = $form->createView();
        }

        return $this->render(
            'shops.html.twig',
            ['forms' => $forms]
        );
    }

    /**
     * @Route("/pull", name="pull")
     */
    public function pull(ShopsDataLoader $loader, ChainParser $parser, ShopRepository $repository): Response
    {
        try {
            # Load and parse shops data
            $shopEntities = [];
            $chains = $loader->load();
            foreach ($chains as $chain) {
                $shopsData = $parser->parse($chain);
                $shopEntities = array_merge(
                    $shopEntities, ShopBuilder::build($shopsData)
                );
            }

            # Save new shops (unexisting externalId
            $em = $this->getDoctrine()->getManager();
            foreach ($shopEntities as $shopEntity) {
                if (!$repository->findBy(['externalId' => $shopEntity->getExternalId()])) {
                    $em->persist($shopEntity);
                }
            }
            $em->flush();
        } catch (\Exception $e) {
            Debug::dump($e);
        }

        return new RedirectResponse('/');
    }
    
        /**
     * @Route("/force", name="force")
     */
    public function force(ShopsDataLoader $loader, ChainParser $parser): Response
    {
        try {
            $shopEntities = $this->parseShopsFromUrl($loader, $parser);

            # Truncate table
            $em = $this->getDoctrine()->getManager();
            $connection = $em->getConnection();
            $platform   = $connection->getDatabasePlatform();
            $connection->executeUpdate($platform->getTruncateTableSQL('shops'));
            
            foreach ($shopEntities as $shopEntity) {
                $em->persist($shopEntity);
            }
            $em->flush();
        } catch (\Exception $e) {
            Debug::dump($e);
        }

        return new RedirectResponse('/');
    }
    
    /**
     * @param ShopRepository $repository
     * @return array
     */
    private function listShops(ShopRepository $repository): array
    {
        $shops = $repository->findBy([], ['externalId' => 'ASC']);
        
        return $shops;
    }
    
    /**
     * @param ShopsDataLoader $loader
     * @param ChainParser $parser
     * @return array
     */
    private function parseShopsFromUrl(ShopsDataLoader $loader, ChainParser $parser): array
    {
        # Load and parse shops data
        $shopEntities = [];
        $chains = $loader->load();
        foreach ($chains as $chain) {
            $shopsData = $parser->parse($chain);
            $shopEntities = array_merge(
                $shopEntities, ShopBuilder::build($shopsData)
            );
        }
        
        return $shopEntities;
    }
}