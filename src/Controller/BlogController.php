<?php

namespace App\Controller;

use App\Entity\ArticleComment;
use App\Form\ArticleCommentType;
use App\Repository\ArticleCommentRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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
    public function showArticle(ArticleRepository $articleRepository, $id, Security $security, Request $request, ArticleCommentRepository $articleCommentRepository): Response
    {
        $article = $articleRepository->findOneBy(["id" => $id]);
        $articleComments = $articleCommentRepository->findAll();
        $articleCategory = $article->getCategory()->getName();

        $comment = new ArticleComment();
        $commentForm = $this->createForm(ArticleCommentType::class, $comment);
        $date = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));
        $comment->setCreatedAt($date);
        $comment->setUser($security->getUser());
        $comment->setArticle($article);
        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid()) {
            $categorySlug = $comment->getArticle()->getCategory()->getCategorySlug();
            $articleCommentRepository->save($comment, true);
            $this->addFlash("commentAdded", "Commentaire ajouté");
            return $this->redirectToRoute("app_category_article", ["categorySlug" => $categorySlug, "id" => $id]);
        }

        return $this->render("blog/articles/article.html.twig", [
            "article" => $article,
            "category" => $articleCategory,
            "articleComments" => $articleComments,
            "commentForm" => $commentForm->createView(),
        ]);
    }

    #[Route("/{categorySlug}/article/{id}/del-comment/{commentId}", name:"app_comment_del")]
    public function delComment(ArticleCommentRepository $articleCommentRepository, $id, $commentId): Response
    {
        $comment = $articleCommentRepository->findOneBy(["id" => $commentId]);
        $categorySlug = $comment->getArticle()->getCategory()->getCategorySlug();
        $comment = $articleCommentRepository->remove($comment, true);
        $this->addFlash("commentaryDeleted", "Le commentaire a été supprimer.");

        return $this->redirectToRoute("app_category_article", ["categorySlug" => $categorySlug, "id" => $id]);
    }
}
