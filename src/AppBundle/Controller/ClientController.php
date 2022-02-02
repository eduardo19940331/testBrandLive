<?php

// src/AppBundle/Controller/ClientController.php
namespace App\Controller;

use App\Entity\Client;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

        $entityManager = $this->getDoctrine()->getManager();
        /** @var Client */
        $client = new Client();
        if ($ident) {
            $client = $entityManager->getRepository(Client::class)->find($ident);
        }

        $client->setCreated(new DateTime());
        $client->setFirstName($firstname);
        $client->setLastName($lastname);
        $client->setEmail($email);
        $client->setDescription($description);
        $client->setEnabled(1);
        $entityManager->persist($client);
        $entityManager->flush();

        // foreach ($gclient as $group) {
        //     $group = 
        // }

        return $this->render('client/new.html.twig', ["client" => $client]);
    }

    /**
     * @Route("/deleted", name="deletedclient")
     */
    public function deletedAction(Request $request): JsonResponse
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
        $nameClient = $client->getFirstName() . " " . $client->getLastName();

        return new JsonResponse([
            "state" => "success",
            "message" => "El Cliente <b>$nameClient</b> se ha eliminado correctamente"
        ]);
    }

    public function showAction(Request $request)
    {
    }
}
