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
     * @Route("/invoice/company-create/{id}/{emptyInvoice}", name="app_company_add")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Company $company = null, Request $request, EntityManagerInterface $em, $emptyInvoice = null, $id = null)
    {
        if($company === null) {
            $company = new Company();
        }
        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Company $company */
            $company = $form->getData();
            $company->setUser($this->getUser());

            $em->persist($company);
            $em->flush();
            if($emptyInvoice !== null) {
                return $this->redirectToRoute('app_invoice_add');
            }
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

    /**
     * @Route("/invoice/company/{id}/edit", name="app_company_edit")
     */
    public function edit(Company $comapny)
    {
        return $this->redirectToRoute('app_company_add', [
            'id' => $comapny->getId(),
        ]);
    }
}