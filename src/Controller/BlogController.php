<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    //////////////////////////////////////////////////////////////
    /////////////////////  POLITIQUE  ////////////////////////////
    //////////////////////////////////////////////////////////////

    #[Route("/politique", name:"politique")]
    public function politique(): Response
    {
        return $this->render('blog/categories/politique.html.twig');
    }

    //////////////////////////////////////////////////////////////
    //////////////////////  ECONOMIE  ////////////////////////////
    //////////////////////////////////////////////////////////////

    #[Route("/economie", name:"economie")]
    public function economie(): Response
    {
        return $this->render('blog/categories/economie.html.twig');
    }

    //////////////////////////////////////////////////////////////
    ////////////////////  GEOPOLITIQUE  //////////////////////////
    //////////////////////////////////////////////////////////////

    #[Route("/geopolitique", name:"geopolitique")]
    public function geopolitique(): Response
    {
        return $this->render('blog/categories/geopolitique.html.twig');
    }

    //////////////////////////////////////////////////////////////
    //////////////////////  SOCIETE  /////////////////////////////
    //////////////////////////////////////////////////////////////

    #[Route("/societe", name:"societe")]
    public function societe(): Response
    {
        return $this->render('blog/categories/societe.html.twig');
    }

    //////////////////////////////////////////////////////////////
    ////////////////  ARTS & LITTERATUR///////////////////////////
    //////////////////////////////////////////////////////////////

    #[Route("/arts-litteratures", name:"artsLitteratures")]
    public function artsLitteratures(): Response
    {
        return $this->render('blog/categories/artsLitteratures.html.twig');
    }

    //////////////////////////////////////////////////////////////
    ////////////////////  PAROLE LIBRE  //////////////////////////
    //////////////////////////////////////////////////////////////

    #[Route("/parole-libre", name:"paroleLibre")]
    public function paroleLibre(): Response
    {
        return $this->render('blog/categories/paroleLibre.html.twig');
    }
}
