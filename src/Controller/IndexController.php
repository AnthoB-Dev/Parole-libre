<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route("/accueil", name:"accueil")]
    public function index(): Response
    {
        return $this->render('index/index.html.twig');
    }

    // Test d'une autre route (pour l'affichage dynamique JS)
    #[Route("/politique", name:"politique")]
    public function politique(): Response
    {
        return $this->render('index/politique.html.twig');
    }
}
