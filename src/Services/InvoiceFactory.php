<?php


namespace App\Services;


use App\Entity\Invoice;
use App\Entity\User;

class InvoiceFactory
{
    const PERIOD = 14;

    public function createInvoice(User $user)
    {
        $invoice = new Invoice();
        $invoice->setCreatedAt(new \DateTime());
        $invoice->setByCompany($user);
        $invoice->setPayTo(new \DateTime('+' . self::PERIOD. 'days', $invoice->getCreatedAt()->getTimezone()));
        $invoice->setInvoiceNumber($this->createInvoiceNumber($user, $invoice));
        return $invoice;
    }

    public function createInvoiceNumber(User $user, Invoice $invoice)
    {
        $number = count($user->getInvoices()) + 1;
        $year = $invoice->getCreatedAt()->format('Y');
        return $year . '/' . $number;
    }
}