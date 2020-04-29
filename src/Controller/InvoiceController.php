<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController extends AbstractController
{
    /**
     * @Route("/invoice/{id}", name="app_invoice")
     */
    public function index(User $user)
    {
        return $this->render('invoice/index.html.twig', [
            'user' => $user,
        ]);
    }
}
