<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class MainController extends AbstractController
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
     * @Route("/", name="main")
     */
    public function index(Request $request)
    {
        return $this->render('main/index.html.twig', []);
    }

    /**
     * @Route("/pl", name="pl")
     */
    public function pl(Request $request)
    {
        $this->setLocale($request, 'pl');

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/en", name="en")
     */
    public function en(Request $request)
    {
        $this->setLocale($request, 'en');

        return $this->redirect($request->headers->get('referer'));
    }

    private function setLocale(Request $request, string $locale): void
    {
        $request->setLocale($locale);
        $request->getSession()->set('_locale', $locale);
    }
}
