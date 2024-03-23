<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\ReportReason;
use App\Form\ArticleType;
use App\Form\CategoryType;
use App\Form\ReportReasonType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\ReportReasonRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{

    #[Route('/', name: 'admin.index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * - Utilitaire \
     * -- Supprime les images de l'upload_directory non utilisées par ArticleRepository.
     */
    #[Route("/func-delete_unused_images", name: "admin.func.deleteUnused")]
    public function deleteUnusedImages(ArticleRepository $articleRepo, Filesystem $fileSystem, ParameterBagInterface $params): RedirectResponse
    {
        $articles = $articleRepo->findAll();
        $uploadDirectory = $params->get("upload_directory");
        $uDImages = array_diff(scandir($uploadDirectory), array('.', '..'));
        foreach ($articles as $article) {
            $image = $article->getImage();
            if (!in_array($image, $uDImages)) {
                $fileSystem->remove($uploadDirectory . "/" . $image);
            }
        }
        $this->addFlash("success", "Admin : Images non utilisées supprimées avec succès");
        return $this->redirectToRoute("accueil");
    }
    
    /**
     * - (Contenu) : 
     */
    #[Route('/contenu', name: 'admin.content')]
    public function content(): Response
    {
        return $this->render('admin/content/content.html.twig');
    }

    /**
     * - (Contenu) Articles : \
     * -- Read : 
     */
    #[Route("/contenu/articles/", name:"admin.articles")]
    public function indexArticles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAllArticles();

        return $this->render("admin/content/articles/index.html.twig", [
            "articles" => $articles,
        ]);
    }

    /**
     * - (Contenu) Articles : \
     * -- Create : 
     */
    #[Route("/contenu/articles/ajouter", name:"admin.articles.add")]
    public function addArticle(ArticleRepository $articleRepository, Request $request, Security $security): Response
    {
        $article = new Article();
        $date = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));
        $user = $security->getUser();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $article->setUser($user);
            $article->setCreatedAt($date);

            // Défini l'image
            $file = $form->get("image")->getData();
            if($file) {
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileExtension = $file->guessExtension();
                $newFileName = $originalFileName . "_" . uniqid() . "." . $fileExtension;
                $file -> move($this->getParameter("upload_directory"), $newFileName);
                $article -> setImage($newFileName);
            } else {
                $article->setImage("000-default_article_image.jpg");
            }

            $articleRepository->save($article, true);

            return $this->redirectToRoute("admin.articles");
        }
        
        return $this->render("admin/content/articles/addArticle.html.twig", [
            "form" => $form,
        ]);
    }

    /**
     * - (Contenu) Articles : \
     * -- Update : 
     */
    #[Route("/contenu/articles/{id}/modifier", name: "admin.articles.edit")]
    public function editArticle(ArticleRepository $articleRepository, $id, Request $request): Response
    {
        $article = $articleRepository->findOneBy(["id" => $id]);
        $date = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt($date);

            // Défini l'image
            $file = $form->get("image")->getData();
            if($file) {
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileExtension = $file->guessExtension();
                $newFileName = $originalFileName . "_" . uniqid() . "." . $fileExtension;
                $file -> move($this->getParameter("upload_directory"), $newFileName);
                $article -> setImage($newFileName);
            }
            
            $articleRepository->save($article, true);

            return $this->redirectToRoute("app_articles_index");
        }
        
        return $this->render("admin/content/articles/editArticle.html.twig", [
            "form" => $form,
            "article" => $article,
        ]);
    }

    /**
     * - (Contenu) Articles : \
     * -- Delete : Supprime un article ainsi que son image associé dans le dossier upload_directory. 
     */
    #[Route("/contenu/articles/{id}/delete", name: "admin.articles.delete", methods:"DELETE")]
    #[IsGranted("ROLE_ADMIN")]
    public function deleteArticle(Article $article, ArticleRepository $articleRepo, ParameterBagInterface $params, Filesystem $fileSystem): RedirectResponse
    {
        $imagePath = $article->getImage();
        $uploadDirectoryPath = $params->get("upload_directory");

        $articleRepo->remove($article, true);
        $fileSystem->remove($uploadDirectoryPath . "/" . $imagePath);

        $this->addFlash("success", "Article supprimé");
        
        return $this->redirectToRoute("admin.articles");
    }

    /**
     * - (Contenu) Commentaires : \
     * -- Read : 
     */
    #[Route('/contenu/commentaires ', name: 'admin.commentaries')]
    public function commentaries(): Response
    {
        return $this->render('admin/content/commentaries.html.twig');
    }

    /**
     * - (Contenu) Utilisateurs : \
     * -- Read : 
     */
    #[Route('/contenu/utilisateurs ', name: 'admin.users')]
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAllAndOrderedByRole("ASC");
        return $this->render('admin/content/users.html.twig', [
            "users" => $users,
        ]);
    }

    /**
     * - (Contenu) Signalements : \
     * -- Read : 
     */
    #[Route('/contenu/signalements', name: 'admin.reports')]
    public function reports(): Response
    {
        return $this->render('admin/content/reports.html.twig');
    }

    /**
     * - (Structure) : \
     */
    #[Route('/structure', name: 'admin.structure')]
    #[IsGranted("ROLE_SUPERADMIN")]
    public function structure(): Response
    {
        return $this->render('admin/structure/structure.html.twig');
    }

    /**
     * - (Structure) Categories : \
     * -- Read : 
     */
    #[Route('/structure/categories', name: 'admin.structure.categories')]
    #[IsGranted("ROLE_SUPERADMIN")]
    public function categories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/structure/categories.html.twig', [
            "categories" => $categories,
        ]);
    }

    /**
     * - (Structure) Categories : \
     * -- Create : 
     */
    #[Route('/structure/categories/ajouter', name: 'admin.structure.categories.add')]
    #[IsGranted("ROLE_SUPERADMIN")]
    public function addCategories(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        
        $categories = $categoryRepository->findAll();

        if($form->isSubmitted() && $form->isValid()) {
            $categoryName = $form->getData()->getName();
            $categoryName = mb_convert_case($categoryName, MB_CASE_TITLE, "UTF-8");
            $category->setName($categoryName);
            $categoryRepository->save($category, true);
            return $this->redirectToRoute("admin.structure.categories.add");
        }

        return $this->render('admin/structure/categoryAdd.html.twig', [
            "form" => $form,
            "categories" => $categories,
        ]);
    }

    /**
     * - (Structure) Signalements : \
     * -- Read :
     */
    #[Route('/structure/signalements', name: 'admin.structure.reportReasons')]
    #[IsGranted("ROLE_SUPERADMIN")]
    public function reportReasons(ReportReasonRepository $reportReasonRepository): Response
    {
        $reportReasons = $reportReasonRepository->findAll();

        return $this->render('admin/structure/reportReasons.html.twig', [
            "reportReasons" => $reportReasons
        ]);
    }

    /**
     * - (Structure) Signalements : \
     * -- Create : 
     */
    #[Route('/structure/signalements/ajouter', name: 'admin.structure.reportReasons.add')]
    #[IsGranted("ROLE_SUPERADMIN")]
    public function addReportReasons(Request $request, ReportReasonRepository $reportReasonRepository): Response
    {
        $reportReason = new ReportReason();
        $form = $this->createForm(ReportReasonType::class, $reportReason);
        $form->handleRequest($request);
        
        $reportReasons = $reportReasonRepository->findAll();

        if($form->isSubmitted() && $form->isValid()) {
            $reportReasonTitle = $form->getData()->getTitle();
            $reportReasonTitle = mb_convert_case($reportReasonTitle, MB_CASE_TITLE, "UTF-8");
            $reportReason->setTitle($reportReasonTitle);
            $reportReasonRepository->save($reportReason, true);
            return $this->redirectToRoute("admin.structure.reportReasons");
        }

        return $this->render('admin/structure/reportReasonAdd.html.twig', [
            "form" => $form,
            "reportReasons" => $reportReasons,
        ]);
    }
}
