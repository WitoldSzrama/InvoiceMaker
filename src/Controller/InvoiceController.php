<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use App\Repository\CompanyRepository;
use App\Repository\InvoiceRepository;
use App\Services\InvoiceFactory;
use App\Services\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class InvoiceController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

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
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('isRemoteEnabled', true);
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('invoice/template.html.twig', [
            'invoice' => $invoice,
        ]);
        $html = mb_convert_encoding($html, 'ASCII');
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $dompdf->stream($invoice->getInvoiceNumberSlug().'.pdf', [
            'Attachment' => true,
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
        if (empty($this->getUser()->getCompanies()->getValues())) {
            $this->addFlash('noCompany', $this->translator->trans('noCompany', [], 'invoice'));

            return $this->redirectToRoute('app_company_add');
        }
        if (null == $invoice) {
            $newInvoice = $invoiceFactory->createInvoice($this->getUser());
        } else {
            if ($invoice->getUser() !== $this->getUser()) {
                throw $this->createAccessDeniedException();
            }
            $newInvoice = clone $invoice;
        }
        $form = $this->createForm(InvoiceType::class, $newInvoice);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $invoiceFactory->onInvoiceSubmitted($newInvoice, $this->getuser());
            $em->persist($newInvoice);
            $em->flush();

            return $this->redirectToRoute('app_invoice_list', [
                'id' => $newInvoice->getId(),
            ]);
        }

        return  $this->render('invoice/create.html.twig', [
            'form' => $form->createView(),
            'noProducts' => empty($this->getUser()->getProducts()->getValues()),
        ]);
    }

    /**
     * @Route("/invoice/list/{id}", name="app_invoice_list")
     */
    public function list(InvoiceRepository $invoiceRepository, Request $request, PaginatorInterface $paginator, Pagination $pagination, Invoice $invoice = null, $id = null)
    {
        $paginator = $pagination->getPagination($invoiceRepository, $request, $paginator, $this->getUser());

        return $this->render('invoice/list.html.twig', [
           'invoices' => $paginator,
           'invoice' => $invoice,
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
}
