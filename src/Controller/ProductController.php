<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Form\ProductType;
use App\Repository\CompanyRepository;
use App\Repository\ProductRepository;
use App\Services\ProductFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/invoice/product-create", name="app_product_add")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, EntityManagerInterface $em, ProductFactory $productFactory)
    {
        $product = $productFactory->createProduct($this->getUser());
        $form = $this->createForm(ProductType::class);

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
     * @Route("/invoice/product-list", name="app_product_list")
     */
    public function list(ProductRepository $productRepository)
    {
        $products = $productRepository->getProductsByUser($this->getUser());

        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }
}
