<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
{
    /**
     * @Route("/invoice/settings", name="app_settings")
     */
    public function index(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(UserType::class, $this->getUser());

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_invoice');
        }

        return $this->render('settings/index.html.twig', [
            'settingsForm' => $form->createView(),
        ]);
    }
}
