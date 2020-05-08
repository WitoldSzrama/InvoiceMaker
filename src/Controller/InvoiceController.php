<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use App\Repository\CompanyRepository;
use App\Repository\InvoiceRepository;
use App\Services\InvoiceFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController extends AbstractController
{
    /**
     * @Route("/invoice", name="app_invoice")
     */
    public function index()
    {
        return $this->render('invoice/index.html.twig');
    }

    /**
     * @Route("/invoice/template/{id}", name="app_template")
     */
    public function template(Invoice $invoice)
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setDpi(150);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('invoice/template.html.twig', [
            'invoice' => $invoice,
        ]);
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
        ]);
        return $this->render('invoice/template.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * @Route("/invoice/create/{id}", name="app_invoice_add")
     */
    public function createInvoice(Request $request, InvoiceFactory $invoiceFactory, EntityManagerInterface $em, CompanyRepository $companyRepository, Invoice $invoice = null, $id = null)
    {
        if(empty($companyRepository->getCompaniesByUser($this->getUser()))) {
            $this->redirectToRoute('app_company_add');
        }
        if ($invoice == null) {
            $invoice = $invoiceFactory->createInvoice($this->getUser());
        }
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($invoice);
            $em->flush();
            return $this->redirectToRoute('app_template', [
                'id' => $invoice->getId(),
            ]);
        }

        return  $this->render('invoice/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/invoice/list", name="app_invoice_list")
     */
    public function list(InvoiceRepository $invoiceRepository)
    {
        $invoices = $invoiceRepository->findAll();

        return $this->render('invoice/list.html.twig', [
           'invoices' => $invoices,
        ]);
    }

    /**
     * @Route("/invoice/{id}/remove", name="app_invoice_item_remove")
     */
    public function remove(Invoice $invoice, EntityManagerInterface $em)
    {
        $em->remove($invoice);
        $em->flush();

        return $this->redirectToRoute('app_invoice_list');
    }

    /**
     * @Route("/invoice/{id}/edit", name="app_invoice_item_edit")
     */
    public function edit(Invoice $invoice)
    {
        return $this->redirectToRoute('app_invoice_add', [
            'id' => $invoice->getId(),
        ]);
    }

}
