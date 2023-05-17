<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/articles", name:"app_articles_")]
class ArticleController extends AbstractController
{
    #[Route("/", name:"index")]
    public function indexArticles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();
        
        return $this->render("articles/index.html.twig", [
            "articles" => $articles,
        ]);
    }

    #[Route("/ajouter-article", name:"add")]
    public function addArticle(ArticleRepository $articleRepository, Request $request, Security $security): Response
    {
        $article = new Article();
        $date = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));
        $user = $security->getUser();
        $userId = $user->getId();

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
                $article->setImage("default.png");
            }

            $articleRepository->save($article, true);

            return $this->redirectToRoute("app_articles_index");
        }
        
        return $this->render("articles/addArticle.html.twig", [
            "form" => $form->createView(),
        ]);
    }
}
