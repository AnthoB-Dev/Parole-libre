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
use App\Repository\CategoryRepository;
use App\Repository\CommentLikeRepository;
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security as Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BlogController extends AbstractController
{
    #[Route("/categorie", name:"category.redirect")]
    #[Route("/auteur")]
    #[Route("/auteur/modifier")]
    public function redirection(): RedirectResponse
    {
        return $this->redirectToRoute("accueil");
    }
    
    // Category - Read : Affiche les articles d'une catégorie (page blog/articles/category.html.twig)
    #[Route("/categorie/{categorySlug}", name:"category.show", requirements: ["categorySlug" => "[a-z0-9-]+"])]
    public function categoryPage(ArticleRepository $articleRepository, CategoryRepository $categoryRepository, string $categorySlug): Response
    {
        if($categorySlug != "accueil") {
            $category = $categoryRepository->findOneBy(["categorySlug" => $categorySlug]);
            $id = $category->getId();
        
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
    #[Route("/categorie/{categorySlug}/{titleSlug}-{id}", name:"article.show", requirements: ["id" => "\d+", "titleSlug" => "[a-z0-9-]+"])]
    #[Route("/categorie/parole-libre/{categorySlug}/{titleSlug}-{id}", name:"article.show.paroleLibre", requirements: ["id" => "\d+", "titleSlug" => "[a-z0-9-]+"])]
    public function showArticle(Security $security, Request $request, ArticleRepository $articleRepository, int $id, ArticleCommentRepository $articleCommentRepository, CategoryRepository $categoryRepository, string $categorySlug, string $titleSlug): Response
    {
        $articleByRouteSlug = $articleRepository->findOneBy(["titleSlug" => $titleSlug]) ?: null;
        $articleByRouteId = $articleRepository->find($id) ?: null;
        $categoryChecker = $categoryRepository->findOneBy(["categorySlug" => $categorySlug]) ?: null;
        $routeIsParoleLibre = strpos($request->getPathInfo(), "/categorie/parole-libre") === 0 ? true : false;

        // Définition de la variable $article selon si le titleSlug ou l'id fourni dans l'url correspond bien à un article existant
        // Dans le cas où il n'y aurait pas de correspondance et que ni le slug ni l'id n'est un article valide
        // Je prépare une redirection, soit vers la catégorie s'il est elle même valide ($categoryChecker), soit vers l'accueil si aucun des paramètres fourni ne sont valident
        if(isset($articleByRouteSlug)) {
            $article = $articleByRouteSlug;

        } elseif(isset($articleByRouteId)) {
            $article = $articleByRouteId;
            
        } elseif(!isset($articleByRouteSlug) && !isset($articleByRouteId)) {

            if($categoryChecker) {
                return $this->redirectToRoute("category.show", [
                    "categorySlug" => $categorySlug
                ]);
            } else {
                return $this->redirectToRoute("accueil");
            }
        }

        // Vérification de l'url pour déterminer si le slug et l'id correspondent bien au même article
        // Si ce n'est pas le cas je redirige une nouvelle fois vers l'article en reparametrant les slugs et l'id de la route de l'article (qui est forcément défini à ce stade grâce aux conditions précedente)
        // Et enfin si l'article est un "Parole Libre" je redirige vers cette fonction mais avec une route différente
        if($articleByRouteId !== $articleByRouteSlug 
           ||  $categorySlug !== $article->getCategory()->getCategorySlug()) {

            return $this->redirectToRoute("article.show", [
                "categorySlug" => $article->getCategory()->getCategorySlug(),
                "titleSlug" => $article->getTitleSlug(),
                "id" => $article->getId(),
            ]);

        } elseif($article->isParoleLibre() && !$routeIsParoleLibre) {
            return $this->redirectToRoute("article.show.paroleLibre", [
                "categorySlug" => $article->getCategory()->getCategorySlug(),
                "titleSlug" => $article->getTitleSlug(),
                "id" => $article->getId(),
            ]);
        }

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
            $this->addFlash("success", "Commentaire ajouté");
            return $this->redirectToRoute("article.show", [
                "categorySlug" => $categorySlug, 
                "titleSlug" => $titleSlug,
                "id" => $id, 
            ]);
        }
        
        foreach($articleComments as $articleComment) {

            $updateForm = $this->createForm(ArticleCommentType::class, $articleComment, [
                'action' => $this->generateUrl('comment.update', [
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
                $this->addFlash('succcess', 'Commentaire mis à jour');
                return $this->redirectToRoute('article.show', [
                    "categorySlug" => $categorySlug, 
                    "titleSlug" => $article->getCategory()->getCategorySlug(),
                    "id" => $id,
                ]);
            }
        }
        return $this->render("blog/articles/article.html.twig", [
            "article" => $article,
            "category" => $articleCategory,
            "articleComments" => $articleComments,
            "commentForm" => $commentForm,
            'updateForms' => $updateForms,
        ]);
    }

    // Article - Create :
    #[Route("/auteur/ajouter", name: "article.new", methods: ["GET", "POST"])]
    public function newParoleLibre(Request $request, Security $security, ArticleRepository $articleRepository): Response
    {
        $article = new Article();
        $date = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));
        $user = $security->getUser();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $article->setUser($user);
            $article->setCreatedAt($date);
            if($security->isGranted('ROLE_AUTHOR')) {
                $article->setParoleLibre(true);
            }
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
            $this->addFlash("success", "Article ajouté avec succès");

            return $this->redirectToRoute("article.show", [
                "categorySlug" => $article->getCategory()->getCategorySlug(), 
                "titleSlug" => $article->getTitleSlug(),
                "id" => $article->getId(),
            ]);
        }

        return $this->render("blog/articles/newArticle.html.twig", [
            "form" => $form,
        ]);
    }

    // Article - Update :
    #[Route("/auteur/modifier/{titleSlug}-{id}", name:"article.edit", requirements: ["id" => "\d+", "titleSlug" => "[a-z0-9-]+"], methods: ["GET", "POST"])]
    public function editParoleLibre(Request $request, Security $security, ArticleRepository $articleRepository, int $id, string $titleSlug): Response
    {
        $articleByRouteSlug = $articleRepository->findOneBy(["titleSlug" => $titleSlug]) ?: null;
        $articleByRouteId = $articleRepository->findOneBy(["id" => $id]) ?: null;

        // Définition de la variable $article selon si le titleSlug ou l'id fourni dans l'url correspond bien à un article existant
        // Dans le cas où il n'y aurait pas de correspondance et que ni le slug ni l'id n'est un article valide, redireciton vers accueil
        if (isset($articleByRouteSlug)) {
            $article = $articleByRouteSlug;
        } elseif (isset($articleByRouteId)) {
            $article = $articleByRouteId;
        } elseif (!isset($articleByRouteSlug) && !isset($articleByRouteId)) {
            return $this->redirectToRoute("accueil");
        }

        // Vérification de l'url pour déterminer si le slug et l'id correspondent bien au même article
        // Si ce n'est pas le cas je redirige une nouvelle fois vers l'article en reparametrant les slugs et l'id de la route de l'article (qui est forcément défini à ce stade grâce aux conditions précedente)
        if ($articleByRouteId !== $articleByRouteSlug) {

            return $this->redirectToRoute("article.edit", [
                "titleSlug" => $article->getTitleSlug(),
                "id" => $article->getId(),
            ]);
        }
        $date = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));
        
        if($security->getUser() == $article->getUser() ); {
            $articleTitle = $article->getTitle();
            $articleImage = $article->getImage();
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
                
                $article->setUpdatedAt($date);
                $this->addFlash("success", "Article modifié avec succès");
                $articleRepository->save($article, true);
                return $this->redirectToRoute("article.show", [
                    "categorySlug" => $article->getCategory()->getCategorySlug(),
                    "titleSlug" => $titleSlug,
                    "id" => $id,
                ]);
            }

            return $this->render("blog/articles/editArticle.html.twig", [
                "form" => $form,
                "articleTitle" => $articleTitle,
                "articleImage" => $articleImage,
                "article" => $article,
            ]);
        }
    }

    // Article (likes) - Create / Read / Delete : Affiche le nombre de j'aime, ajoute ou retire un j'aime d'un article
    #[Route("/categorie/{categorySlug}/{titleSlug}-{id}/like", name:"article.like", requirements: ["id" => "\d+", "titleSlug" => "[a-z0-9-]+"])]
    public function toggleArticleLike(Security $security, ArticleRepository $articleRepository, ArticleLikeRepository $articleLikeRepository, string $categorySlug, int $id, ): RedirectResponse
    {
        $currentUser = $security->getUser();
        $article = $articleRepository->findOneBy(["id" => $id]);
        $articleLike = $articleLikeRepository->findOneBy([
            'user' => $currentUser,
            'article' => $id,
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

        return $this->redirectToRoute("article.show", [
            "categorySlug" => $categorySlug, 
            "titleSlug" => $article->getTitleSlug(),
            "id" => $id,
        ]);
    }

    // Commentaires - Update : Modification d'un commentaire posté
    #[Route("/categorie/{categorySlug}/{titleSlug}-{id}/comment/{commentId}/update", name:"comment.update", requirements: ["id" => "\d+", "titleSlug" => "[a-z0-9-]+"], methods: ["GET", "POST"])]
    public function updateComment(Request $request, Security $security, ArticleCommentRepository $articleCommentRepository, int $commentId, string $categorySlug, int $id, string $titleSlug): Response
    {
        $comment = $articleCommentRepository->findOneBy(["id" => $commentId]);
        $date = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));

        if($security->getUser() == $comment->getUser()) {

            $updateForm = $this->createForm(ArticleCommentType::class, $comment);
            $updateForm->handleRequest($request);
    
            if($updateForm->isSubmitted() && $updateForm->isValid()) {
                $comment->setUpdatedAt($date);
                $articleCommentRepository->save($comment, true);
                $this->addFlash('success', 'Commentaire mis à jour');
                return $this->redirectToRoute('article.show', [
                    'categorySlug' => $categorySlug, 
                    "titleSlug" => $titleSlug,
                    'id' => $id,
                ]);
            }
    
            return $this->render('blog/articles/article.html.twig', [
                'updateForm' => $updateForm,
            ]);
        }
    }

    // Commentaires - Delete : Suppression d'un commentaire
    #[Route("/categorie/{categorySlug}/{titleSlug}-{id}/comment/{commentId}/delete", name:"comment.delete", requirements: ["id" => "\d+", "titleSlug" => "[a-z0-9-]+"], methods: ["DELETE"])]
    public function delComment(Security $security, ArticleCommentRepository $articleCommentRepository, int $id, int $commentId, string $categorySlug, string $titleSlug): Response
    {
        $comment = $articleCommentRepository->findOneBy(["id" => $commentId]);
        if($security->getUser() == $comment->getUser() || $security->isGranted("ROLE_ADMIN")) {
            $comment = $articleCommentRepository->remove($comment, true);
            $this->addFlash("success", "Le commentaire a été supprimé.");
        } else {
            $this->addFlash("error", "Le commentaire n'a pas été supprimé car vous n'êtes pas son auteur.");
        }

        return $this->redirectToRoute("article.show", [
            "categorySlug" => $categorySlug, 
            "titleSlug" => $titleSlug,
            "id" => $id,
        ]);
    }

    // Commentaires (likes) - Create / Read / Delete : Affiche le nombre de j'aime, ajoute ou retire un j'aime d'un commentaire
    #[Route("/categorie/{categorySlug}/{titleSlug}-{id}/{commentId}/like", name:"comment.like", requirements: ["id" => "\d+", "titleSlug" => "[a-z0-9-]+"])]
    public function toggleCommentLike(Security $security, CommentLikeRepository $commentLikeRepository, ArticleCommentRepository $articleCommentRepository, int $commentId, int $id, string $categorySlug, string $titleSlug): RedirectResponse
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

        return $this->redirectToRoute("article.show", [
            "categorySlug" => $categorySlug, 
            "titleSlug" => $titleSlug,
            "id" => $id,
        ]);
    }
}
