<?php

namespace App\Controller;

use App\Store;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(Store $store): Response
    {

        return $this->render('accueil/index.html.twig', [
            'evenements' => $store->getEvenement(),
        ]);
    }
    #[Route('/statistiques', name: 'app_statistiques')]
    public function statistiques(Store $store): Response
    {
        return $this->render('statistiques/index.html.twig', [

            'stats' => $store->getStatistiques(),
            'guichets_fermes' => $store->getCompteEvenement(),
        ]);
    }
}
