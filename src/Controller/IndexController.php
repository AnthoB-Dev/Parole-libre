<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security as Security;

class IndexController extends AbstractController
{
    #[Route("/accueil", name:"accueil")]
    public function index(ArticleRepository $articleRepository, CategoryRepository $categoryRepository, UserRepository $userRepo): Response
    {
        $displayedCategories = [];
        $categories = $categoryRepository->findAll();
        foreach($categories as $category) {
            if($category->getName() !== "Parole Libre") {
                $displayedCategories[] = $category->getId();
            }
        }
         
        /**
         * Défini le nombre d'articles "hero" à envoyé sur la page d'accueil
         */
        $recentHeroArticles = $articleRepository->findArticlesByRecentlyPublishedAndByCategories(3, $displayedCategories);
        /**
         * Défini le nombre d'articles "Derniers ajouts" à envoyé sur la page d'accueil
         */
        $articles = $articleRepository->findArticlesRecentlyPublishedByCategories(2, $displayedCategories);
        /**
         * Défini le nombre d'articles "Populaires" à envoyé sur la page d'accueil
         */
        $popularArticles = $articleRepository->findByPopularityOfCategories(6, $displayedCategories);
        /**
         * Défini le nombre d'articles de la catégorie "Parole Libre à afficher dans la sidebar de l'accueil
         */ 
        $lastParoles = $articleRepository->findArticlesOfParoleLibre(5);
        /**
         * Défini le nombre de "Derniers commentaires" de la catégorie "Parole Libre à afficher dans la sidebar de l'accueil
         */ 
        $lastComments = $articleRepository->findArticlesByRecentComments(10);

        $users = $userRepo->findAllAndOrderedByRole("ASC"); // TODO: remove - debug
        $usersToSwitch = [$users[0], $users[9], $users[15],]; // TODO: remove - debug

        return $this->render("index/accueil.html.twig", [
            "recentHeroArticles" => $recentHeroArticles,
            "articles" => $articles,
            "popularArticles" => $popularArticles,
            "lastParoles" => $lastParoles,
            "lastComments" => $lastComments,
            "usersToSwitch" => $usersToSwitch, // TODO: remove
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
