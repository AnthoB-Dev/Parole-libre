<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route("/accueil", name:"accueil")]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAllArticles();

        return $this->render('index/accueil.html.twig', [
            "articles" => $articles,
        ]);
    }

}
