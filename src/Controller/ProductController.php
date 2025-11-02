<?php

namespace App\Controller;

use App\Service\ProductProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    private ProductProvider $provider;

    public function __construct(ProductProvider $provider)
    {
        $this->provider = $provider;
    }

    public function list(): Response
    {
        $products = $this->provider->getProducts();

        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }

   
}