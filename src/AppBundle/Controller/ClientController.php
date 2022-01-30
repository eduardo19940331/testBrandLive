<?php

// src/AppBundle/Controller/ClientController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends Controller
{
    /**
     * @Route("/index", name="client")
     */
    public function indexAction()
    {
        return $this->render('client/client.html.twig', []);
    }

    /**
     * @Route("/create", name="createclient")
     */
    public function createAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $client = [];

        return $this->render('client/new.html.twig', ["client" => $client]);
    }
}
