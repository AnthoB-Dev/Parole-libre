<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/", name:"app_articles_")]
class ArticleController extends AbstractController
{    

    #[Route("/", name:"index")]
    public function indexArticles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAllArticles();

        return $this->render("index/index.html.twig", [
            "articles" => $articles,
        ]);
    }
}
