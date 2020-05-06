<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/product/{id}", name="api")
     */
    public function apiProduct(Product $product)
    {
        return $this->json($product);
    }
}
