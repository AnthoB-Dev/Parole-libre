<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleComment;
use App\Entity\ArticleLike;
use App\Entity\CommentLike;
use App\Form\ArticleCommentType;
use App\Form\ArticleType;
use App\Repository\ArticleCommentRepository;
use App\Repository\ArticleLikeRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentLikeRepository;
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security as Security;
use Cocur\Slugify\Slugify;

class BlogController extends AbstractController
{    
    // Articles - Read : Affiche les articles d'une catégorie (page blog/articles/category.html.twig)
    #[Route("articles/{categorySlug}/{id}", name:"app_category")]
    public function categoryPage(ArticleRepository $articleRepository, $id, $categorySlug): Response
    {
        if($categorySlug != "parole-libre" && $id != 8) {
            $heroArticles = $articleRepository->findArticlesByRecentlyPublishedAndByCategory(3, $id);
            $articles = $articleRepository->findAllArticlesByCategoryId($id);
            $category = $articles[0]->getCategory()->getName();
            $lastCategoryComments = $articleRepository->findArticlesByCategoryAndRecentComments(10, $id);
        } else {
            $heroArticles = $articleRepository->findArticlesByRecentlyPublishedAndByParoleLibre(3);
            $articles = $articleRepository->findAllArticlesOfParoleLibre();
            $category = "Parole Libre";
            $lastCategoryComments = $articleRepository->findParolesLibresAndRecentComments(10);
        }
        
        return $this->render('blog/articles/category.html.twig', [
            "heroArticles" => $heroArticles,
            "articles" => $articles,
            "category" => $category,
            "lastCategoryComments" => $lastCategoryComments,
        ]);
    }

    // Article - Read : Page de lecture d'un article (blog/articles/article.html.twig)
    // Commentaires - Create : Ajout d'un commentaire sur le dit article
    // Commentaires - Read : Récupères les commentaires lié à l'article
    // Commentaires - Update : Prépare un form de modification pour chaque commentaire présent, puis appel la fonction updateComment pour gérer la modification 
    #[Route("/{categorySlug}/article/{id}/{titleSlug}", name:"app_category_article")]
    public function showArticle(Security $security, Request $request, ArticleRepository $articleRepository, $id, ArticleCommentRepository $articleCommentRepository, $categorySlug, $titleSlug): Response
    {
        $article = $articleRepository->findOneBy(["id" => $id]);
        $articleComments = $articleCommentRepository->findBy(["article" => $id], ["createdAt" => "DESC"]);
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
            return $this->redirectToRoute("app_category_article", [
                "categorySlug" => $categorySlug, 
                "id" => $id, 
                "titleSlug" => $titleSlug
            ]);
        }
        
        foreach($articleComments as $articleComment) {

            $updateForm = $this->createForm(ArticleCommentType::class, $articleComment, [
                'action' => $this->generateUrl('app_article_comment_update', [
                    "categorySlug" => $categorySlug,
                    "id" => $articleComment->getArticle()->getId(),
                    "titleSlug" => $titleSlug, 
                    "commentId" => $articleComment->getId(),
                ]),
                'method' => 'POST',
            ]);
            $updateForm->handleRequest($request);
            $updateForms[$articleComment->getId()] = $updateForm->createView();
    
            if($updateForm->isSubmitted() && $updateForm->isValid()) {
                $articleCommentRepository->save($articleComment, true);
                $this->addFlash('commentUpdated', 'Commentaire mis à jour');
                return $this->redirectToRoute('app_category_article', [
                    "categorySlug" => $categorySlug, 
                    "id" => $id,
                    "titleSlug" => $article->getCategory()->getCategorySlug(),
                ]);
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

    // Article - Create :
    #[Route("/writer/parole-libre/ajouter", name: "app_article_new")]
    public function newParoleLibre(Security $security, Request $request, ArticleRepository $articleRepository): Response
    {
        $article = new Article();
        $date = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));
        $user = $security->getUser();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $article->setUser($user);
            $article->setCreatedAt($date);
            $slugify = new Slugify();
            $slug = $slugify->slugify($article->getTitle());
            $article->setTitleSlug($slug);
            $article->setParoleLibre(true);

            // Défini l'image
            $file = $form->get("image")->getData();
            if($file) {
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileExtension = $file->guessExtension();
                $newFileName = $originalFileName . "_" . uniqid() . "." . $fileExtension;
                $file -> move($this->getParameter("upload_directory"), $newFileName);
                $article -> setImage($newFileName);
            } else {
                $article->setImage("default.png");
            }

            $articleRepository->save($article, true);

            return $this->redirectToRoute("app_category_article", [
                "categorySlug" => $article->getCategory()->getCategorySlug(), 
                "id" => $article->getId(),
                "titleSlug" => $article->getTitleSlug(),
            ]);
        }

        return $this->render("blog/articles/newArticle.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    // Article - Update :
    #[Route("/writer/parole-libre/article/{id}/modifier/{titleSlug}", name: "app_article_edit")]
    public function editParoleLibre(ArticleRepository $articleRepository, Request $request, $id, Security $security, $titleSlug): Response
    {
        $article = $articleRepository->findOneBy(["id" => $id]);
        $slugify = new Slugify();
        $date = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));
        
        if($security->getUser() == $article->getUser() ); {
            $articleTitle = $article->getTitle();
            $articleImage = $article->getImage();
            $slug = $slugify->slugify($articleTitle);
            $form = $this->createForm(ArticleType::class, $article);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $file = $form->get("image")->getData();
                if($file) {
                    $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $fileExtension = $file->guessExtension();
                    $newFileName = $originalFileName . "_" . uniqid() . "." . $fileExtension;
                    $file->move($this->getParameter("upload_directory"), $newFileName);
                    $article->setImage($newFileName);
                }
                
                $article->setTitleSlug($slug);
                $article->setUpdatedAt($date);

                $articleRepository->save($article, true);
                return $this->redirectToRoute("app_category_article", [
                    "categorySlug" => $article->getCategory()->getCategorySlug(),
                    "id" => $id,
                    "titleSlug" => $titleSlug,
                ]);
            }

            return $this->render("blog/articles/editArticle.html.twig", [
                "form" => $form->createView(),
                "articleTitle" => $articleTitle,
                "articleImage" => $articleImage,
                "article" => $article,
            ]);
        }
    }

    // Article (likes) - Create / Read / Delete : Affiche le nombre de j'aime, ajoute ou retire un j'aime d'un article
    #[Route("/{categorySlug}/article/{id}/{titleSlug}/like-article/{articleId}", name:"app_article_like_add")]
    public function toggleArticleLike(ArticleRepository $articleRepository, Security $security, $categorySlug, $articleId, ArticleLikeRepository $articleLikeRepository)
    {
        
        $currentUser = $security->getUser();     
        $article = $articleRepository->findOneBy(["id" => $articleId]);
        $articleLike = $articleLikeRepository->findOneBy([
            'user' => $currentUser,
            'article' => $articleId,
        ]);
        
        if($articleLike) {
            $userLiker = $articleLike->getUser();
        }
        
        if($articleLike && $userLiker == $currentUser) {
            $articleLikeRepository->remove($articleLike, true);
    
        } else {
            $like = new ArticleLike(); 
            $like->setUser($currentUser);
            $like->setArticle($article);
            $articleLikeRepository->save($like, true);
        }

        return $this->redirectToRoute("app_category_article", [
            "categorySlug" => $categorySlug, 
            "id" => $articleId,
            "titleSlug" => $article->getTitleSlug(),
        ]);
    }

    // Commentaires - Update : Modification d'un commentaire posté
    #[Route("/{categorySlug}/article/{id}/{titleSlug}/comment/{commentId}/update", name:"app_article_comment_update")]
    public function updateComment(Request $request, ArticleCommentRepository $articleCommentRepository, $commentId, Security $security, $categorySlug, $id, $titleSlug): Response
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
                return $this->redirectToRoute('app_category_article', [
                    'categorySlug' => $categorySlug, 
                    'id' => $id,
                    "titleSlug" => $titleSlug,
                ]);
            }
    
            return $this->render('blog/articles/article.html.twig', [
                'updateForm' => $updateForm->createView(),
            ]);
        }
    }

    // Commentaires - Delete : Suppression d'un commentaire
    #[Route("/{categorySlug}/article/{id}/{titleSlug}/comment/{commentId}/delete", name:"app_article_comment_del")]
    public function delComment(ArticleCommentRepository $articleCommentRepository, $id, $commentId, $categorySlug, $titleSlug): Response
    {
        $comment = $articleCommentRepository->findOneBy(["id" => $commentId]);
        $comment = $articleCommentRepository->remove($comment, true);
        $this->addFlash("commentaryDeleted", "Le commentaire a été supprimé.");

        return $this->redirectToRoute("app_category_article", [
            "categorySlug" => $categorySlug, 
            "id" => $id,
            "titleSlug" => $titleSlug,
        ]);
    }

    // Commentaires (likes) - Create / Read / Delete : Affiche le nombre de j'aime, ajoute ou retire un j'aime d'un commentaire
    #[Route("/{categorySlug}/article/{id}/{titleSlug}/like-comment/{commentId}", name:"app_comment_like_add")]
    public function toggleCommentLike(CommentLikeRepository $commentLikeRepository, $commentId, Security $security, $id, $categorySlug, ArticleCommentRepository $articleCommentRepository, $titleSlug)
    {
        
        $currentUser = $security->getUser();     
        $comment_id = $articleCommentRepository->findOneBy(["id" => $commentId]);
        $commentLike = $commentLikeRepository->findOneBy([
            'user' => $currentUser,
            'article_comment' => $comment_id,
        ]);

        if($commentLike) {
            $userLiker = $commentLike->getUser();
        }

        if(!$commentLike) {
            $like = new CommentLike();  
            $like->setUser($security->getUser());
            $like->setArticleComment($comment_id);
            $commentLikeRepository->save($like, true);
    
        } else if($commentLike && $userLiker == $currentUser) {
            $commentLikeRepository->remove($commentLike, true);
            
        } else {
            $like = new CommentLike(); 
            $like->setUser($currentUser);
            $like->setArticleComment($comment_id);
            $commentLikeRepository->save($like, true);
        }

        return $this->redirectToRoute("app_category_article", [
            "categorySlug" => $categorySlug, 
            "id" => $id,
            "titleSlug" => $titleSlug,
        ]);
    }

    // #[Route("/updateAll")] 
    // public function updateAll(ArticleRepository $articleRepository)
    // {
    //     $articles = $articleRepository->findAll();
    //     foreach ($articles as $article) {
    //         $imageCaption = $article->getImageCaption();
    //         $title = $article->getTitle();
    //         $titleSlug = $article->getTitleSlug();
    //         $image = $article->getImage();
            
    //         if(!$imageCaption) {
    //             $article->setImageCaption("Légende d'image par défaut");
    //         }
    //         if(!$titleSlug) {
    //             $slugify = new Slugify();
    //             $slug = $slugify->slugify($title);
    //             $article->setTitleSlug($slug);
    //         }
    //         if($image == "default.webp") {
    //             $article->setImage("default.jpg");
    //         }
    //         if($title) {
    //             $tl = strtolower($title);
    //             $ucf = ucfirst($tl);
    //             $article->setTitle($ucf);
    //         }
    //         if($article->isParoleLibre() == null) {
    //             $article->setParoleLibre(0);
    //         }
            
    //         $articleRepository->save($article, true);
            
    //     }
    //     return $this->redirectToRoute("accueil");
    // }
}
