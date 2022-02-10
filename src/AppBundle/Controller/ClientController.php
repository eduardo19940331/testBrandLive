<?php

// src/AppBundle/Controller/ClientController.php
namespace App\Controller;

use App\Entity\Client;
use App\Entity\GroupCategory;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ClientType;
use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/panel", name="panel_")
 */
class ClientController extends Controller
{
    /**
     * @Route("/index", name="client")
     */
    public function indexAction()
    {

        return $this->render('client/client.html.twig');
    }

    /**
     * @Route("/search", name="searchClient")
     */
    public function searchAction(Request $request): JsonResponse
    {
        $search = $request->get('search', "");
        $entityManager = $this->getDoctrine()->getManager();
        $data = $entityManager->getRepository(Client::class)->getAllClient($search);

        return new JsonResponse([
            "state" => "success",
            "data" => $data
        ]);
    }

    /**
     * @Route("/create", name="createclient")
     */
    public function createAction()
    {
        $client = new Client();
        $form = $this->makeCreateForm($client);

        return $this->render('client/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Funcion Crea un formulario para el registro de Clientes
     */
    private function makeCreateForm(Client $client)
    {
        $groupsOptions = $this->getOptionsGroups();

        $form = $this->createForm(ClientType::class, $client, [
            'action' => $this->generateUrl('saveclient'),
            'method' => 'POST',
            'groupsc' => $groupsOptions
        ]);

        return $form;
    }


    /**
     * @Route("/edit", name="editclient")
     */
    public function editAction(string $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $client = $entityManager->getRepository(Client::class)->find($id);

        $form = $this->makeEditForm($client);

        return $this->render('client/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Funcion Crea un formulario para el registro de Clientes
     */
    private function makeEditForm(Client $client)
    {
        $groupsOptions = $this->getOptionsGroups();

        $form = $this->createForm(ClientType::class, $client, [
            'action' => $this->generateUrl('saveclient'),
            'method' => 'POST',
            'id' => $client->getId(),
            'groupsc' => $groupsOptions
        ]);

        return $form;
    }

    private function getOptionsGroups(): array
    {
        $entityManager = $this->getDoctrine()->getManager();
        return $entityManager->getRepository(GroupCategory::class)->gepOptionsGroups();
    }

    /**
     * @Route("/save", name="saveclient")
     * @ParamConverter("id", class="Appb:Client")
     */
    public function saveAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $response = $request->get('client', []);
        $ident = $response['id'] ?? null;
        $client = $entityManager->getRepository(Client::class)->find($ident);
        if (!$client) {
            $client = new Client();
            $client->setCreated(new DateTime());
        }
        $groupsOption = $entityManager->getRepository(GroupCategory::class)->gepOptionsGroups();

        $form = $this->createForm(ClientType::class, $client, ['groupsc' => $groupsOption]);
        $form->handleRequest($request);

        $validator = $this->get('validator');
        $error = $validator->validate($client);
        if (count($error) > 0) {
            $errorsString = (string)$error;
            return new Response($errorsString);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();
        }

        return $this->redirect($this->generateUrl('showclient', ['id' => $client->getId()]));
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
            return new JsonResponse([
                "state" => "error",
                "message" => "El Cliente no ha sido encontrado en el sistema"
            ]);
        }
        $client->setEnabled(0);
        $client->setDeleted(new DateTime());
        $entityManager->persist($client);
        $entityManager->flush();
        $nameClient = $client->getFirstName() . " " . $client->getLastName();

        return new JsonResponse([
            "state" => "success",
            "message" => "El Cliente <b>$nameClient</b> se ha eliminado correctamente"
        ]);
    }

    /**
     * @Route("/show", name="showclient")
     */
    public function showAction(string $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $client = $entityManager->getRepository(Client::class)->find($id);
        if (!$client) {
            throw new Exception("El Cliente Consultado no se encuentra", 1);
        }
        $selectGroup = [];
        foreach ($client->getClientGroups() as $gc) {
            if (($gc->getGroup()->getEnabled() && $gc->getEnabled())) {
                $selectGroup[] = $gc->getGroup()->getId();
            }
        }
        $client->selectGroup = $selectGroup;

        $groups = $entityManager->getRepository(GroupCategory::class)->getGroupsAsOptions();

        return $this->render('client/show.html.twig', [
            "client" => $client,
            "groups" => $groups
        ]);
    }
}
