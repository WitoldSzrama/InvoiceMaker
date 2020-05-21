<?php

namespace App\Services;

use App\Entity\Company;
use App\Entity\Invoice;
use App\Entity\Users;
use DateTime;

class InvoiceFactory
{
    const PERIOD = 14;

    public function createInvoice(Users $user)
    {
        $invoice = new Invoice();
        $invoice->setUser($user);
        $invoice->setByCompany($this->createCompanyFromUser($user, $invoice));

        return $invoice;
    }

    private function createInvoiceNumber(Users $user, Invoice $invoice)
    {
        $baseYear = $user->getYear();
        $baseMonth = $user->getMonth();
        $saleTime = $invoice->getSalesDate();
        if ($baseYear < (int)$saleTime->format('Y')) {
            $user->setYear((int)$saleTime->format('Y'));
            $user->setBaseNumber(1);
        }

        if ($user->getIsMonth() && $baseMonth < (int)$saleTime->format('m')) {
            $user->setYear((int)$saleTime->format('m'));
            $user->setBaseNumber(1);
        }

        $number = $user->getBaseNumber();
        $year = $invoice->getCreatedAt()->format('Y');
        $month = $invoice->getCreatedAt()->format('m');

        $user->setBaseNumber($user->getBaseNumber() + 1);
        
        return $this->createInvoiceNumberFromTemplate($user->getInvoiceNumberTemplate(), $number, $year, $month);
    }

    public function createCompanyFromUser(Users $user, Invoice $invoice)
    {
        $company = new Company();
        $company->setContactEmail($user->getEmail());
        $company->setName($user->getName());
        $company->setNip($user->getNip());
        $company->setRegon($user->getRegon());
        $company->setCity($user->getCity());
        $company->setPostCode($user->getPostCode());
        $company->setStreet($user->getStreet());
        $company->setStNumber($user->getStNumber());
        $company->setAccountNumber($user->getAccountNumber());

        return $company;
    }

    public function onInvoiceSubmitted(Invoice $invoice, Users $user)
    {
        $invoice->setInvoiceNumber($this->createInvoiceNumber($user, $invoice));
        $payTo = new DateTime($invoice->getSalesDate()->format('Y-m-d'));
        $payTo->modify('+14 days');
        $invoice->setPayTo($payTo);
    }

    private function createInvoiceNumberFromTemplate(string $template, string $number, string $year, string $month): string
    {
        $invoiceNumber = str_replace('$Y', $year, $template);
        $invoiceNumber = str_replace('$M', $month, $invoiceNumber);
        $invoiceNumber = str_replace('$N', $number, $invoiceNumber);

        return $invoiceNumber;
    }
}
