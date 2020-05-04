<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use App\Repository\CompanyRepository;
use App\Services\InvoiceFactory;
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
     * @Route("/invoice/template", name="app_template")
     */
    public function template()
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setDpi(150);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('invoice/template.html.twig');
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
        ]);

        return $this->render('invoice/template.html.twig');
    }

    /**
     * @Route("/invoice/create", name="app_invoice_create")
     */
    public function createInvoice(Request $request, InvoiceFactory $invoiceFactory, CompanyRepository $companyRepository, Invoice $invoice = null)
    {
        if ($invoice == null) {
            $invoice = $invoiceFactory->createInvoice($this->getUser());
        }
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            dd($form->getData());
        }

        return  $this->render('invoice/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
