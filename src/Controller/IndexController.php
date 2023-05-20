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
    public function index(ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {        
        $articles = $articleRepository->findAllArticles();
        $recentHeroArticles = $articleRepository->findArticlesByRecentlyPublishedAndByCategories(3, 1, 2, 5, 6, 7);

        $categoryPolitique = $categoryRepository->findOneBy(["id" => 1])->getName();
        $recentPolitiqueArticles = $articleRepository->findArticlesByRecentlyPublishedAndByCategory(2, 1);

        $categoryArtsLitteratures = $categoryRepository->findOneBy(["id" => 2])->getName();
        $recentArtsLitteraturesArticles = $articleRepository->findArticlesByRecentlyPublishedAndByCategory(2, 2);

        $categoryEconomie = $categoryRepository->findOneBy(["id" => 5])->getName();
        $recentEconomieArticles = $articleRepository->findArticlesByRecentlyPublishedAndByCategory(2, 5);

        $categorySociete = $categoryRepository->findOneBy(["id" => 6])->getName();
        $recentSocieteArticles = $articleRepository->findArticlesByRecentlyPublishedAndByCategory(2, 6);

        $categoryGeopolitique = $categoryRepository->findOneBy(["id" => 7])->getName();
        $recentGeopolitiqueArticles = $articleRepository->findArticlesByRecentlyPublishedAndByCategory(2, 7);

        return $this->render("index/accueil.html.twig", [
            "articles" => $articles,
            "recentHeroArticles" => $recentHeroArticles,

            "categoryPolitique" => $categoryPolitique,
            "recentPolitiqueArticles" => $recentPolitiqueArticles,

            "categoryArtsLitteratures" => $categoryArtsLitteratures,
            "recentArtsLitteraturesArticles" => $recentArtsLitteraturesArticles,

            "categoryEconomie" => $categoryEconomie,
            "recentEconomieArticles" => $recentEconomieArticles,

            "categorySociete" => $categorySociete,
            "recentSocieteArticles" => $recentSocieteArticles,

            "categoryGeopolitique" => $categoryGeopolitique,
            "recentGeopolitiqueArticles" => $recentGeopolitiqueArticles,
        ]);
    }

}
