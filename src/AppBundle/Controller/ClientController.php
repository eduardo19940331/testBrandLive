<?php

// src/AppBundle/Controller/ClientController.php
namespace App\Controller;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends Controller
{
    /**
     * @Route("/index", name="client")
     */
    public function indexAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $clients = $entityManager->getRepository(Client::class)->getAllClient();

        return $this->render('client/client.html.twig', ["clients" => $clients]);
    }

    /**
     * @Route("/create", name="createclient")
     */
    public function createAction()
    {
        return $this->render('client/new.html.twig');
    }

    /**
     * @Route("/edit", name="editclient")
     */
    public function editAction(string $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $client = $entityManager->getRepository(Client::class)->find($id);

        return $this->render('client/new.html.twig', ["client" => $client]);
    }

    /**
     * @Route("/save", name="saveclient")
     */
    public function saveAction(Request $request)
    {
        $ident = $request->get('ident');
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $email = $request->get('email');
        $description = $request->get('description');
        $gclient = $request->get('gclient');
        echo '<pre>';
        print_r($ident);
        print_r($firstname);
        print_r($lastname);
        print_r($email);
        print_r($description);
        print_r($gclient);
        echo '</pre>';
        die();
        $entityManager = $this->getDoctrine()->getManager();
        $client = [];

        return $this->render('client/new.html.twig', ["client" => $client]);
    }

    /**
     * @Route("/deleted", name="deletedclient")
     */
    public function deletedAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $ident = $request->get('ident');
        $client = $entityManager->getRepository(Client::class)->find($ident);
        if (!$client) {
            return json_encode([
                "state" => "error",
                "message" => "El Cliente no ha sido encontrado en el sistema"
            ]);
        }
        $client->setEnabled(0);
        $entityManager->persist($client);
        $entityManager->flush();

        return json_encode([
            "state" => "success",
            "message" => "El Cliente se ha eliminado correctamente"
        ]);
    }
}
