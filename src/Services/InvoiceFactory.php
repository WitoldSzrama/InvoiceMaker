<?php

namespace App\Services;

use App\Entity\Company;
use App\Entity\Invoice;
use App\Entity\User;
use PhpParser\Node\Expr\Cast\String_;

class InvoiceFactory
{
    const PERIOD = 14;

    public function createInvoice(User $user)
    {
        $invoice = new Invoice();
        $invoice->setCreatedAt(new \DateTime());
        $invoice->setUser($user);
        $invoice->setByCompany($this->createCompanyFromUser($user, $invoice));
        $invoice->setPayTo(new \DateTime('+' . self::PERIOD. 'days', $invoice->getCreatedAt()->getTimezone()));
        $invoice->setInvoiceNumber($this->createInvoiceNumber($user, $invoice));

        return $invoice;
    }

    private function createInvoiceNumber(User $user, Invoice $invoice)
    {
        if(count($user->getInvoices()) === 0){
            $number = $user->getBaseNumber();
        } else {
            $number = count($user->getInvoices()) + $user->getBaseNumber();
        }
        $year = $invoice->getCreatedAt()->format('Y');
        $month = $invoice->getCreatedAt()->format('m');

        return $this->createInvoiceNumberFromTemplate($user->getInvoiceNumberTemplate(), $number, $year);
    }


    public function createCompanyFromUser(User $user,Invoice $invoice)
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

    private function createInvoiceNumberFromTemplate(string $template,string $number,string $year): string
    {
        
        $invoiceNumber = str_replace('$Y', $year, $template);
        $invoiceNumber = str_replace('$M', $year, $invoiceNumber);
        $invoiceNumber = str_replace('$N', $number, $invoiceNumber);

        return $invoiceNumber;
    }
}