<?php

namespace App\Controller;

use App\Entity\ArticleComment;
use App\Entity\CommentLike;
use App\Form\ArticleCommentType;
use App\Form\ArticleType;
use App\Repository\ArticleCommentRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentLikeRepository;
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
    public function showArticle(Security $security, Request $request, ArticleRepository $articleRepository, $id, ArticleCommentRepository $articleCommentRepository, $categorySlug, CommentLikeRepository $commentLikeRepository): Response
    {
        $article = $articleRepository->findOneBy(["id" => $id]);
        $articleComments = $articleCommentRepository->findBy(["article" => $id]);
        $articleCategory = $article->getCategory()->getName();
        
        $comment = new ArticleComment();
        $date = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));
        
        $commentForm = $this->createForm(ArticleCommentType::class, $comment);
        $commentForm->handleRequest($request);
        
        if($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt($date);
            $comment->setUser($security->getUser());
            $comment->setArticle($article);
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

    #[Route("/{categorySlug}/article/{id}/suppr/{commentId}", name:"app_comment_del")]
    public function delComment(ArticleCommentRepository $articleCommentRepository, $id, $commentId, $categorySlug): Response
    {
        $comment = $articleCommentRepository->findOneBy(["id" => $commentId]);
        $comment = $articleCommentRepository->remove($comment, true);
        $this->addFlash("commentaryDeleted", "Le commentaire a été supprimé.");

        return $this->redirectToRoute("app_category_article", ["categorySlug" => $categorySlug, "id" => $id]);
    }

    #[Route("/{categorySlug}/article/{id}/edit/{commentId}", name:"app_comment_edit")]
    public function editComment(ArticleCommentRepository $articleCommentRepository, $id, $commentId, Request $request): Response
    {
        $comment = $articleCommentRepository->findOneBy(["id" => $commentId]);
        $categorySlug = $comment->getArticle()->getCategory()->getCategorySlug();
        $editForm = $this->createForm(ArticleType::class, $comment);
        $editForm->handleRequest($request);

        if($editForm->isSubmitted() && $editForm->isValid()) {
            dd($comment);
            $articleCommentRepository->save($comment, true);
        }

        $this->addFlash("commentaryEdited", "Le commentaire a été modifié.");

        return $this->redirectToRoute("app_category_article", ["categorySlug" => $categorySlug, "id" => $id]);
    }

    #[Route("/{categorySlug}/{id}/like-comment/{commentId}", name:"app_comment_like_add")]
    public function toggleCommentLike(CommentLikeRepository $commentLikeRepository, $commentId, Security $security, $id, $categorySlug, ArticleCommentRepository $articleCommentRepository)
    {
        
        $currentUser = $security->getUser();     
        $comment_id = $articleCommentRepository->findOneBy(["id" => $commentId]);
        $commentLike = $commentLikeRepository->findOneBy([
            'user' => $currentUser,
            'article_comment' => $comment_id,
        ]);

        $status = "unliked";

        if($commentLike) {
            $userLiker = $commentLike->getUser();
        }

        if(!$commentLike) {
            $like = new CommentLike();  
            $like->setUser($security->getUser());
            $like->setArticleComment($comment_id);
            $commentLikeRepository->save($like, true);
            $status = "liked";
    
        } else if($commentLike && $userLiker == $currentUser) {
            $commentLikeRepository->remove($commentLike, true);
            $status = "unliked";
            
        } else {
            $like = new CommentLike(); 
            $like->setUser($currentUser);
            $like->setArticleComment($comment_id);
            $commentLikeRepository->save($like, true);
            $status = "liked";
        }

        return $this->redirectToRoute("app_category_article", [
            "categorySlug" => $categorySlug, "id" => $id,
            "status" => $status,
        ]);
    }
}
