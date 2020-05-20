<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/product/{id}", name="api_product", methods={"POST"})
     */
    public function apiProduct(Product $product, SerializerInterface $serializer, $id = 0)
    {
        $jsonProduct = $serializer->serialize($product, 'json', ['groups' => 'apiProduct']);

        return $this->json($jsonProduct);
    }

    /**
     * @Route("/api/product/{id}/remove", name="api_product_remove", methods={"POST"})
     */
    public function apiProductRemove(Product $product, EntityManagerInterface $em, $id = 0)
    {
        $em->remove($product);

        return $this->json('{Product removed}');
    }
}
