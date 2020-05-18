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
use Symfony\Component\HttpFoundation\Session\Session;
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

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('invoice/template.html.twig', [
            'invoice' => $invoice,
        ]);
        $html = mb_convert_encoding($html, "ASCII");
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // Render the HTML as PDF
        $dompdf->render();

        $dompdf->stream($invoice->getInvoiceNumberSlug().'.pdf');
    }

    /**
     * @Route("/invoice/create/{id}", name="app_invoice_add")
     */
    public function createInvoice(Request $request, InvoiceFactory $invoiceFactory, EntityManagerInterface $em, CompanyRepository $companyRepository, Invoice $invoice = null, $id = null)
    {
        if(empty($this->getUser()->getCompanies()->getValues())) {
            $this->addFlash('noCompany', $this->translator->trans('noCompany', [], 'invoice'));

            return $this->redirectToRoute('app_company_add');
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

            return $this->redirectToRoute('app_invoice_list', [
                'id' => $invoice->getId(),
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
