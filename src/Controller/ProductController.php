<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Services\Pagination;
use App\Services\ProductFactory;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/invoice/product/list", name="app_product_list")
     */
    public function list(ProductRepository $productRepository, Request $request, PaginatorInterface $paginator, Pagination $pagination)
    {
        $paginator = $pagination->getPagination($productRepository, $request, $paginator, $this->getUser());

        return $this->render('product/list.html.twig', [
            'products' => $paginator,
        ]);
    }

    /**
     * @Route("/invoice/product/{id}/edit", name="app_product_edit")
     */
    public function edit(Product $product)
    {

        return $this->redirectToRoute('app_product_add', [
            'id' => $product->getId(),
        ]);
    }

    /**
     * @Route("/invoice/product/{id}/remove", name="app_product_item_remove")
     */
    public function remove(Product $product, EntityManagerInterface $em)
    {
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('app_product_list');
    }
}
