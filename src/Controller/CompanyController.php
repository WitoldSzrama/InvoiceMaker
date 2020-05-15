<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use App\Services\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CompanyController extends AbstractController
{
    /**
     * @Route("/invoice/company-create/{id}", name="app_company_add")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Company $company = null, Request $request, EntityManagerInterface $em, $id = null)
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
             
            return $this->redirect($this->getRefererUrl($request));
        }

        return $this->render('company/new.html.twig', [
            'companyForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/invoice/company-list", name="app_company_list")
     */
    public function list(CompanyRepository $companyRepository, Request $request, PaginatorInterface $paginator, Pagination $pagination)
    {
        $paginator = $pagination->getPagination($companyRepository, $request, $paginator, $this->getUser());

        return $this->render('company/list.html.twig', [
            'companies' => $paginator,
        ]);
    }

    /**
     * @Route("/invoice/company/{id}", name="app_company_show")
     */
    public function showCompany(Company $company, EntityManagerInterface $em)
    {
        return $this->render('company/show.html.twig', [
            'company' => $company,
        ]);
    }

    /**
     * @Route("/invoice/company/{id}/remove", name="app_company_remove")
     */
    public function remove(Company $company, EntityManagerInterface $em)
    {
        if (empty($company->getInvoices()->getValues())) {
            $em->remove($company);
        } else {
            $company->setUser(null);
        }
        $em->flush();
        return $this->redirectToRoute('app_company_list');
    }

    private function getRefererUrl(Request $request)
    {
        $referer = $request->request->get('referer');
        $baseInvoiceUrl = $this->generateUrl('app_invoice', [] , UrlGeneratorInterface::ABSOLUTE_URL);
        if ($referer === $baseInvoiceUrl) {
            return $this->generateUrl('app_invoice_add');
        }

        return $referer;
    }
}