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
        $invoice->setPayTo(new \DateTime('+' . self::PERIOD,$invoice->getCreatedAt()));

    }
}