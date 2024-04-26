<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/prices', name: 'app_prices')]
    public function index(): Response
    {
        return $this->render('page/prices.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/terms-conditions', name: 'app_terms_conditions')]
    public function terms(): Response
    {
        return $this->render('page/terms_conditions.html.twig', [
        ]);
    }

    #[Route('/gdpr-info', name: 'app_gdpr_info')]
    public function gdpr(): Response
    {
        return $this->render('page/gdpr_info.html.twig', [
        ]);
    }

    #[Route('/information', name: 'app_information')]
    public function information(): Response
    {
        return $this->render('page/information.html.twig', [
        ]);
    }
}
