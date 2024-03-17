<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security as Security;

class IndexController extends AbstractController
{
    // #[Route("/")]
    #[Route("/accueil", name:"accueil")]
    public function index(ArticleRepository $articleRepository): Response
    {
        $recentHeroArticles = $articleRepository->findArticlesByRecentlyPublishedAndByCategories(3, 1, 2, 5, 6, 7);
        $articles = $articleRepository->findArticlesRecentlyPublishedByCategories(2, 1, 2, 5, 6, 7);
        $popularArticles = $articleRepository->findByPopularityOfCategories(6, 1, 2, 5, 6, 7);
        $lastParoles = $articleRepository->findArticlesByRecentlyPublishedAndByCategory(10, 8);
        $lastComments = $articleRepository->findArticlesByRecentComments(10);

        return $this->render("index/accueil.html.twig", [
            "recentHeroArticles" => $recentHeroArticles,
            "articles" => $articles,
            "popularArticles" => $popularArticles,
            "lastParoles" => $lastParoles,
            "lastComments" => $lastComments,
        ]);
    }

    #[Route("/profil", name:"profil")]
    public function showProfile(Security $security): Response
    {
        $user = $security->getUser();

        return $this->render("index/profil.html.twig", [
            "user" => $user,
        ]);
    }

}
