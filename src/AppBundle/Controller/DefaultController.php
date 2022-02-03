<?php

// src/AppBundle/Controller/DefaultController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="indexPageClient")
     */
    public function homeAction()
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        return $this->render('default/login/login.html.twig');
    }
}
