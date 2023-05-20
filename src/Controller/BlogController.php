<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    //////////////////////////////////////////////////////////////
    /////////////////////  POLITIQUE  ////////////////////////////
    //////////////////////////////////////////////////////////////

    #[Route("/politique", name:"app_category_politique")]
    public function politique(): Response
    {
        return $this->render('blog/categories/politique.html.twig');
    }

    //////////////////////////////////////////////////////////////
    //////////////////////  ECONOMIE  ////////////////////////////
    //////////////////////////////////////////////////////////////

    #[Route("/economie", name:"app_category_economie")]
    public function economie(): Response
    {
        return $this->render('blog/categories/economie.html.twig');
    }

    //////////////////////////////////////////////////////////////
    ////////////////////  GEOPOLITIQUE  //////////////////////////
    //////////////////////////////////////////////////////////////

    #[Route("/geopolitique", name:"app_category_geopolitique")]
    public function geopolitique(): Response
    {
        return $this->render('blog/categories/geopolitique.html.twig');
    }

    //////////////////////////////////////////////////////////////
    //////////////////////  SOCIETE  /////////////////////////////
    //////////////////////////////////////////////////////////////

    #[Route("/societe", name:"app_category_societe")]
    public function societe(): Response
    {
        return $this->render('blog/categories/societe.html.twig');
    }

    //////////////////////////////////////////////////////////////
    ///////////////  ARTS & LITTERATURE //////////////////////////
    //////////////////////////////////////////////////////////////

    #[Route("/arts-litteratures", name:"app_category_artsLitteratures")]
    public function artsLitteratures(): Response
    {
        return $this->render('blog/categories/artsLitteratures.html.twig');
    }

    //////////////////////////////////////////////////////////////
    ////////////////////  PAROLE LIBRE  //////////////////////////
    //////////////////////////////////////////////////////////////

    #[Route("/parole-libre", name:"app_category_paroleLibre")]
    public function paroleLibre(): Response
    {
        return $this->render('blog/categories/paroleLibre.html.twig');
    }

    #[Route("/{category}/article/{id}", name:"app_category_article")]
    public function showArticle(ArticleRepository $articleRepository, $id): Response
    {
        $article = $articleRepository->findOneBy(["id" => $id]);
        $articleCategory = $article->getCategory()->getName();

        return $this->render("blog/articles/article.html.twig", [
            "article" => $article,
            "category" => $articleCategory,
        ]);
    }
}
