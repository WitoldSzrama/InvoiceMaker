<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    /**
     * @Route("/invoice/company-create", name="app_company_add")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(CompanyType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Company $company */
            $company = $form->getData();
            $company->setUser($this->getUser());

            $em->persist($company);
            $em->flush();
            return $this->redirectToRoute('app_invoice');
        }

        return $this->render('company/new.html.twig', [
            'companyForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/invoice/company-list", name="app_company_list")
     */
    public function list(CompanyRepository $companyRepository)
    {
        $companies = $companyRepository->getCompaniesByUser($this->getUser());

        return $this->render('company/list.html.twig', [
            'companies' => $companies,
        ]);
    }
}