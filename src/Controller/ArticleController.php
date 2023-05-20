<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/", name:"app_articles_")]
class ArticleController extends AbstractController
{    

    // #[Route("/", name:"index")]
    // public function indexArticles(ArticleRepository $articleRepository): Response
    // {        
    //     $articles = $articleRepository->findAllArticles();
    //     $recentHeroArticles = $articleRepository->findArticlesByRecentlyPublishedAndByCategories(1, 2, 5, 6, 7);
    //     $recentPolitiqueArticles = $articleRepository->findArticlesByRecentlyPublishedAndByCategory(1);
    //     $recentArtsLitteraturesArticles = $articleRepository->findArticlesByRecentlyPublishedAndByCategory(2);
    //     $recentEconomieArticles = $articleRepository->findArticlesByRecentlyPublishedAndByCategory(5);
    //     $recentSocieteArticles = $articleRepository->findArticlesByRecentlyPublishedAndByCategory(6);
    //     $recentGeopolitiqueArticles = $articleRepository->findArticlesByRecentlyPublishedAndByCategory(7);

    //     return $this->render("index/accueil.html.twig", [
    //         "articles" => $articles,
    //         "recentHeroArticles" => $recentHeroArticles,
    //         "recentPolitiqueArticles" => $recentPolitiqueArticles,
    //         "recentArtsLitteraturesArticles" => $recentArtsLitteraturesArticles,
    //         "recentEconomieArticles" => $recentEconomieArticles,
    //         "recentSocieteArticles" => $recentSocieteArticles,
    //         "recentGeopolitiqueArticles" => $recentGeopolitiqueArticles,
    //     ]);
    // }
}
