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
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security as Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class BlogController extends AbstractController
{
    #[Route("/categorie", name:"category.redirect")]
    #[Route("/auteur")]
    #[Route("/auteur/modifier")]
    public function redirection(): RedirectResponse
    {
        return $this->redirectToRoute("accueil");
    }
    
    /**
     * - Category : \
     * -- Read : Affiche les articles d'une catégorie (page blog/articles/category.html.twig)
     */
    #[Route("/categorie/{categorySlug}", name:"category.show", requirements: ["categorySlug" => "[a-z0-9-]+"], methods: ["GET", "POST"])]
    #[Route("/categorie/parole-libre/{categorySlug}", name:"category.show.paroleLibre", methods: ["GET", "POST"])]
    public function categoryPage(Request $request, ArticleRepository $articleRepository, CategoryRepository $categoryRepository, string $categorySlug): Response
    {
        $currentRoute = $request->attributes->get('_route');

        if($currentRoute === "category.show") {

            $category = $categoryRepository->findOneBy(["categorySlug" => $categorySlug]);
            $id = $category->getId();
    
            $heroArticles = $articleRepository->findArticlesByCategory(3, $id);
            $articles = $articleRepository->findAllArticlesByCategoryId($id);
            $category = $articles[0]["cName"];
            $lastCategoryComments = $articleRepository->findArticlesByCategoryAndRecentComments(10, $id);

        } elseif($currentRoute === "category.show.paroleLibre") {

            if($categorySlug !== "articles") {
                $category = $categoryRepository->findOneBy(["categorySlug" => $categorySlug]);
                $category ?: $category = $categoryRepository->findOneBy(["categorySlug" => "parole-libre"]);
                $id = $category->getId();
    
                $heroArticles = $articleRepository->findArticlesByCategory(3, $id, true);
                $articles = $articleRepository->findAllArticlesByCategoryId($id, true);
                $category = "Parole Libre - " . $category->getName();
                $lastCategoryComments = $articleRepository->findArticlesByCategoryAndRecentComments(10, $id, true);
                
            } else {
                $heroArticles = $articleRepository->findArticlesOfParoleLibre(3);
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

    /**
     * - Article : \
     * -- Read : Page de lecture d'un article (blog/articles/article.html.twig). 
     * - Commentaires : \
     * -- Create : Ajout d'un commentaire sur le dit article. \
     * -- Read : Récupères les commentaires lié à l'article. \
     * -- Update : Prépare un form de modification pour chaque commentaire présent, puis appel la fonction updateComment pour gérer la modification.
     */
    #[Route("/categorie/{categorySlug}/{titleSlug}-{id}", name:"article.show", requirements: ["id" => "\d+", "titleSlug" => "[a-z0-9-]+"], methods: ["GET", "POST", "DELETE"])]
    #[Route("/categorie/parole-libre/{categorySlug}/{titleSlug}-{id}", name:"article.show.paroleLibre", requirements: ["id" => "\d+", "titleSlug" => "[a-z0-9-]+"], methods: ["GET", "POST", "DELETE"])]
    public function showArticle(Security $security, Request $request, SerializerInterface $serializer, ArticleRepository $articleRepository, int $id, ArticleCommentRepository $articleCommentRepository, CategoryRepository $categoryRepository, string $categorySlug, string $titleSlug): Response
    {
        $currentRoute = $request->attributes->get("_route");
        $articleByRouteSlug = $articleRepository->findOneBy(["titleSlug" => $titleSlug]) ?: null;
        $articleByRouteId = $articleRepository->find($id) ?: null;
        $categoryChecker = $categoryRepository->findOneBy(["categorySlug" => $categorySlug]) ?: null;
        
        if($currentRoute === "article.show") {

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

            if($articleByRouteId !== $articleByRouteSlug ||  
                   $categorySlug !== $article->getCategory()->getCategorySlug()) {
                return $this->redirectToRoute("article.show", [
                    "categorySlug" => $article->getCategory()->getCategorySlug(),
                    "titleSlug" => $article->getTitleSlug(),
                    "id" => $article->getId(),
                ]);
            } elseif($article->isParoleLibre()) {
                return $this->redirectToRoute("article.show.paroleLibre", [
                    "categorySlug" => $article->getCategory()->getCategorySlug(),
                    "titleSlug" => $article->getTitleSlug(),
                    "id" => $article->getId(),
                ]);
            }

        } elseif($currentRoute === "article.show.paroleLibre") {

            if(isset($articleByRouteSlug)) {
                $article = $articleByRouteSlug;
            } elseif(isset($articleByRouteId)) {
                $article = $articleByRouteId;
            } elseif(!isset($articleByRouteSlug) && !isset($articleByRouteId)) {
                if($categoryChecker) {
                    return $this->redirectToRoute("category.show.paroleLibre", [
                        "categorySlug" => $categorySlug
                    ]);
                } else {
                    return $this->redirectToRoute("accueil");
                }
            }
    
            if($articleByRouteId !== $articleByRouteSlug ||  
                   $categorySlug !== $article->getCategory()->getCategorySlug()) {
                return $this->redirectToRoute("article.show.paroleLibre", [
                    "categorySlug" => $article->getCategory()->getCategorySlug(),
                    "titleSlug" => $article->getTitleSlug(),
                    "id" => $article->getId(),
                ]);
            }
        }
        
        $articleComments = $articleCommentRepository->findBy(["article" => $article->getId()]);
        $category = $article->getCategory();
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
            
            $updateForm = $this->createForm(ArticleCommentType::class, $comment, [
                'action' => $this->generateUrl('comment.update', [
                    "categorySlug" => $categorySlug,
                    "id" => $article->getId(),
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
            "category" => $category,
            "articleComments" => $articleComments,
            "commentForm" => $commentForm,
            'updateForms' => $updateForms,
        ]);
    }

    /**
     * - Article : \
     * -- Create : Ajout d'un article.
     */
    #[Route("/auteur/ajouter", name: "article.add", methods: ["GET", "POST"])]
    public function newParoleLibre(Request $request, Article $article, ArticleRepository $articleRepository, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $file = $form->get("image")->getData() ?: "000-default_article_image.jpg";

            if($file !== "000-default_article_image.jpg") {

                $date = new DateTime();
                $formatedDate = $date->format("U"); // Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->guessExtension();
                
                $newFileName =
                    $formatedDate
                    . "-"
                    . strtolower(str_replace([" ", "-"], "_", $fileName)
                    . "-"
                    . uniqid()
                    . "."
                    . $extension)
                ;

                $file->move($this->getParameter("upload_directory_images_articles"), $newFileName);
                $article->setImage($newFileName);
            } else {
                $article->setImage("000-default_article_image.jpg");
            }

            $articleRepository->save($article, true);
            $this->addFlash("success", "Article ajouté avec succès.");
            
            return $this->redirectToRoute("article.show", [
                "categorySlug" => $article->getCategory()->getCategorySlug(), 
                "titleSlug" => $article->getTitleSlug(),
                "id" => $article->getId(),
            ]);

        } elseif($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash("error", "L'article n'a pu être ajouté car le contenu n'est pas valide.");
        }

        return $this->render("blog/articles/newArticle.html.twig", [
            "form" => $form,
        ]);
    }

    /**
     * - Article : \
     * -- Update : Modification d'un article posté.
     */
    #[Route("/auteur/modifier/{titleSlug}-{id}", name:"article.edit", requirements: ["id" => "\d+", "titleSlug" => "[a-z0-9-]+"], methods: ["GET", "POST"])]
    #[IsGranted("ROLE_WRITER")]
    public function editParoleLibre(Request $request, Security $security, ParameterBagInterface $params, Filesystem $fileSystem, Article $article, ArticleRepository $articleRepository, int $id, string $titleSlug): Response
    {
        if($security->getUser() === $article->getUser() || $security->isGranted("ROLE_ADMIN")) {
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
            
            $form = $this->createForm(ArticleType::class, $article);
            $form->handleRequest($request);
            
            if($form->isSubmitted() && $form->isValid()) {
                
                $oldImage = $article->getImage();
                $file = $form->get("image")->getData();
                $date = new DateTime();
                $formatedDate = $date->format("U"); // Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)

                if(empty($file) || $file->getClientOriginalName() !== $oldImage) { 
                    $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->guessExtension();
                    $newFileName = 
                        $formatedDate 
                        . "-" 
                        . strtolower(str_replace([" ", "-"], "_", $fileName) 
                        . "-" 
                        . uniqid() 
                        . "." 
                        . $extension)
                    ;
                    
                    $file->move($this->getParameter("upload_directory_images_articles"), $newFileName);
                    $fileSystem->remove($params->get("upload_directory_images_articles") . "/" . $oldImage);
                    $fileSystem->remove($params->get("upload_directory") . "/" . $oldImage);
                    $article->setImage($newFileName);
                } 
                $this->addFlash("success", "Article modifié avec succès.");
                
                $articleRepository->save($article, true);

                return $this->redirectToRoute("article.show.paroleLibre", [
                    "categorySlug" => $article->getCategory()->getCategorySlug(),
                    "titleSlug" => $article->getTitleSlug(),
                    "id" => $article->getId(),
                ]);
            }
    
            return $this->render("blog/articles/editArticle.html.twig", [
                "form" => $form,
                "articleTitle" => $article->getTitle(),
                "articleImage" => $article->getImage(),
                "article" => $article,
            ]);
        }

        $this->addFlash("error", "Vous ne pouvez pas modifé cet article car vous n'en n'êtes pas l'auteur.");
        return $this->redirectToRoute("article.show", [
            "categorySlug" => $article->getCategory()->getCategorySlug(),
            "titleSlug" => $article->getTitleSlug(),
            "id" => $article->getId(),
        ]);
    }

    /**
     * - Article likes : \
     * -- Create : Ajout d'un like sur un article. \
     * -- Read : Affiche le nombre de likes d'un article. \
     * -- Delete : Affiche le nombre de j'aime, ajoute ou retire un j'aime d'un article.
     */
    #[Route("/categorie/{categorySlug}/{titleSlug}-{id}/like", name:"article.like", requirements: ["id" => "\d+", "titleSlug" => "[a-z0-9-]+"], methods: ["GET", "POST", "DELETE"])]
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

    /**
     * - Commentaires : \
     * -- Update : Modification d'un commentaire posté.
     */
    #[Route("/categorie/{categorySlug}/{titleSlug}-{id}/{commentId}/update", name:"comment.update", requirements: ["id" => "\d+", "titleSlug" => "[a-z0-9-]+"], methods: ["GET", "POST"])]
    public function updateComment(Request $request, Security $security, ArticleCommentRepository $articleCommentRepository, int $commentId, string $categorySlug, int $id, string $titleSlug): Response
    {
        $comment = $articleCommentRepository->findOneBy(["id" => $commentId]);
        $date = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));

        if($security->getUser() === $comment->getUser() || $security->isGranted("ROLE_ADMIN")) {

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

    /**
     * - Commentaires : \
     * -- Delete : Suppression d'un commentaire.
     */
    #[Route("/categorie/{categorySlug}/{titleSlug}-{id}/{commentId}/delete", name:"comment.delete", requirements: ["id" => "\d+", "titleSlug" => "[a-z0-9-]+"], methods: ["DELETE"])]
    public function delComment(Security $security, ArticleCommentRepository $articleCommentRepository, int $id, int $commentId, string $categorySlug, string $titleSlug): RedirectResponse
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

    /**
     * - Commentaires likes : \
     * -- Create : Ajout d'un like sur un commentaire. \
     * -- Read : Affiche le nombre de likes d'un commentaire.\
     * -- Delete : Affiche le nombre de j'aime, ajoute ou retire un j'aime d'un commentaire.
     */
    #[Route("/categorie/{categorySlug}/{titleSlug}-{id}/{commentId}/like", name:"comment.like", requirements: ["id" => "\d+", "titleSlug" => "[a-z0-9-]+"], methods: ["GET", "POST", "DELETE"])]
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
