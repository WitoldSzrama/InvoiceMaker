<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Services\ProductFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/invoice/product/form/{id}", name="app_product_add")
     */
    public function create( Product $product = null, Request $request, EntityManagerInterface $em, ProductFactory $productFactory, $id = null)
    {
        if($product === null) {
            $product = $productFactory->createProduct($this->getUser());
        }
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Company $company */
            $product = $form->getData();
            $product->setUser($this->getUser());

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('app_product_list');
        }

        return $this->render('product/new.html.twig', [
            'productForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/invoice/product/list", name="app_product_list")
     */
    public function list(ProductRepository $productRepository)
    {
        $products = $productRepository->getProductsByUser($this->getUser());

        return $this->render('product/list.html.twig', [
            'products' => $products,
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
