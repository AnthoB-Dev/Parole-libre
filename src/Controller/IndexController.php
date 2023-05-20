<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route("/accueil", name:"accueil")]
    public function index(ArticleRepository $articleRepository): Response
    {        
        $recentHeroArticles = $articleRepository->findArticlesByRecentlyPublishedAndByCategories(3, 1, 2, 5, 6, 7);

        $articles = $articleRepository->findArticlesRecentlyPublishedByCategories(2, 1, 2, 5, 6, 7);

        return $this->render("index/accueil.html.twig", [
            "recentHeroArticles" => $recentHeroArticles,
            
            "articles" => $articles,
        ]);
    }

}
