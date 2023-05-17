<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\ReportReason;
use App\Form\CategoryType;
use App\Form\ReportReasonType;
use App\Repository\CategoryRepository;
use App\Repository\ReportReasonRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'app_admin_')]
#[Security('is_granted("ROLE_ADMIN")')]
class AdminController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }


    //      Partie "contenu"
    #[Route('/contenu', name: 'content')]
    public function content(): Response
    {
        return $this->render('admin/content/content.html.twig');
    }

    #[Route('/contenu/articles', name: 'articles')]
    public function articles(): Response
    {
        return $this->render('admin/content/articles.html.twig');
    }

    #[Route('/contenu/commentaires ', name: 'commentaries')]
    public function commentaries(): Response
    {
        return $this->render('admin/content/commentaries.html.twig');
    }

    #[Route('/contenu/utilisateurs ', name: 'users')]
    public function users(): Response
    {
        return $this->render('admin/content/users.html.twig');
    }

    #[Route('/contenu/signalements', name: 'reports')]
    public function reports(): Response
    {
        return $this->render('admin/content/reports.html.twig');
    }


    //      Partie "structure"
    #[Route('/structure', name: 'structure')]
    public function structure(): Response
    {
        return $this->render('admin/structure/structure.html.twig');
    }

    #[Route('/structure/categories', name: 'structure_categories')]
    public function categories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/structure/categories.html.twig', [
            "categories" => $categories,
        ]);
    }

    #[Route('/structure/categories/add', name: 'structure_categories_add')]
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
            return $this->redirectToRoute("app_admin_structure_categories_add");
        }

        return $this->render('admin/structure/categoryAdd.html.twig', [
            "form" => $form->createView(),
            "categories" => $categories,
        ]);
    }

    #[Route('/structure/signalements', name: 'structure_reportReasons')]
    public function reportReasons(ReportReasonRepository $reportReasonRepository): Response
    {
        $reportReasons = $reportReasonRepository->findAll();

        return $this->render('admin/structure/reportReasons.html.twig', [
            "reportReasons" => $reportReasons
        ]);
    }

    #[Route('/structure/signalements/add', name: 'structure_reportReasons_add')]
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
            return $this->redirectToRoute("app_admin_structure_reportReasons_add");
        }

        return $this->render('admin/structure/reportReasonAdd.html.twig', [
            "form" => $form->createView(),
            "reportReasons" => $reportReasons,
        ]);
    }
}
