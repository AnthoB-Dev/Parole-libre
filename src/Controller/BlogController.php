<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{    
    #[Route("/{categorySlug}/{id}", name:"app_category")]
    public function categoryPage(ArticleRepository $articleRepository, $id): Response
    {
        $heroArticles = $articleRepository->findArticlesByRecentlyPublishedAndByCategory(3, $id);
        $articles = $articleRepository->findAllArticlesByCategoryId($id);

        return $this->render('blog/articles/category.html.twig', [
            "heroArticles" => $heroArticles,
            "articles" => $articles,
        ]);
    }

    #[Route("/{categorySlug}/article/{id}", name:"app_category_article")]
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
