<?php

namespace App\Controller;

use App\Entity\ArticleComment;
use App\Entity\CommentLike;
use App\Form\ArticleCommentType;
use App\Form\ArticleType;
use App\Repository\ArticleCommentRepository;
use App\Repository\ArticleRepository;
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
    // Articles - Read : Affiche les articles d'une catégorie (page blog/articles/category.html.twig)
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

    // Article - Read : Page de lecture d'un article (blog/articles/article.html.twig)
    // Commentaires - Create : Ajout d'un commentaire sur le dit article
    // Commentaires - Read : Récupères les commentaires lié à l'article
    // Commentaires - Update : Prépare un form de modification pour chaque commentaire présent, puis appel la fonction updateComment pour gérer la modification 
    #[Route("/{categorySlug}/article/{id}", name:"app_category_article")]
    public function showArticle(Security $security, Request $request, ArticleRepository $articleRepository, $id, ArticleCommentRepository $articleCommentRepository, $categorySlug): Response
    {
        $article = $articleRepository->findOneBy(["id" => $id]);
        $articleComments = $articleCommentRepository->findBy(["article" => $id]);
        $articleCategory = $article->getCategory()->getName();
        $updateForms = [];
        
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
        
        foreach($articleComments as $articleComment) {

            $updateForm = $this->createForm(ArticleCommentType::class, $articleComment, [
                'action' => $this->generateUrl('app_article_comment_update', [
                    "categorySlug" => $categorySlug,
                    'id' => $articleComment->getArticle()->getId(), 
                    "commentId" => $articleComment->getId()
                ]),
                'method' => 'POST',
            ]);
            $updateForm->handleRequest($request);
            $updateForms[$articleComment->getId()] = $updateForm->createView();
    
            if($updateForm->isSubmitted() && $updateForm->isValid()) {
                $articleCommentRepository->save($articleComment, true);
                $this->addFlash('commentUpdated', 'Commentaire mis à jour');
                return $this->redirectToRoute('app_category_article', ['categorySlug' => $categorySlug, 'id' => $id]);
            }
        }
        
        return $this->render("blog/articles/article.html.twig", [
            "article" => $article,
            "category" => $articleCategory,
            "articleComments" => $articleComments,
            "commentForm" => $commentForm->createView(),
            'updateForms' => $updateForms,
        ]);
    }

    // Commentaires - Update : Modification d'un commentaire posté
    #[Route("/{categorySlug}/article/{id}/comment/{commentId}/update", name:"app_article_comment_update")]
    public function updateComment(Request $request, ArticleCommentRepository $articleCommentRepository ,$commentId, Security $security, $categorySlug, $id): Response
    {
        $comment = $articleCommentRepository->findOneBy(["id" => $commentId]);
        $date = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));

        if($security->getUser() == $comment->getUser()) {

            $updateForm = $this->createForm(ArticleCommentType::class, $comment);
            $updateForm->handleRequest($request);
    
            if($updateForm->isSubmitted() && $updateForm->isValid()) {
                $comment->setUpdatedAt($date);
                $articleCommentRepository->save($comment, true);
                $this->addFlash('commentUpdated', 'Commentaire mis à jour');
                return $this->redirectToRoute('app_category_article', ['categorySlug' => $categorySlug, 'id' => $id]);
            }
    
            return $this->render('blog/articles/article.html.twig', [
                'updateForm' => $updateForm->createView(),
            ]);
        }
    }

    // Commentaires - Delete : Suppression d'un commentaire
    #[Route("/{categorySlug}/article/{id}/comment/{commentId}/delete", name:"app_article_comment_del")]
    public function delComment(ArticleCommentRepository $articleCommentRepository, $id, $commentId, $categorySlug): Response
    {
        $comment = $articleCommentRepository->findOneBy(["id" => $commentId]);
        $comment = $articleCommentRepository->remove($comment, true);
        $this->addFlash("commentaryDeleted", "Le commentaire a été supprimé.");

        return $this->redirectToRoute("app_category_article", ["categorySlug" => $categorySlug, "id" => $id]);
    }

    // Commentaires - Create / Read / Delete : Affiche le nombre de j'aime, ajoute ou retire un j'aime d'un commentaire
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
            "categorySlug" => $categorySlug, 
            "id" => $id,
        ]);
    }
}
