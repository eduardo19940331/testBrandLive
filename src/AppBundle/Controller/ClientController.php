<?php

// src/AppBundle/Controller/ClientController.php
namespace App\Controller;

use App\Entity\Client;
use App\Entity\ClientGroup;
use App\Entity\GroupCategory;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
        $groups = $this->getOptionsGroups();

        return $this->render('client/new.html.twig', ["groups" => $groups]);
    }

    /**
     * @Route("/edit", name="editclient")
     */
    public function editAction(string $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $client = $entityManager->getRepository(Client::class)->find($id);
        $selectGroup = [];
        foreach ($client->getClientGroups() as $gc) {
            if (($gc->getGroup()->getEnabled() && $gc->getEnabled())) {
                $selectGroup[] = $gc->getGroup()->getId();
            }
        }
        $client->selectGroup = $selectGroup;
        $groups = $this->getOptionsGroups();

        return $this->render('client/new.html.twig', [
            "client" => $client,
            "groups" => $groups
        ]);
    }

    private function getOptionsGroups(): array
    {
        $entityManager = $this->getDoctrine()->getManager();
        return $entityManager->getRepository(GroupCategory::class)->getGroupsAsOptions();
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

        $clientGroupAlter = [];
        foreach ($gclient as $group) {
            $group = $entityManager->getRepository(GroupCategory::class)->find($group);

            $clientGroup = $entityManager->getRepository(ClientGroup::class)->findOneBy([
                "client" => $client->getId(),
                "group" => $group->getId()
            ]);
            if (!$clientGroup) {
                $clientGroup = new ClientGroup();
            }

            $clientGroup->setCreated(new DateTime());
            $clientGroup->setClient($client);
            $clientGroup->setGroup($group);
            $clientGroup->setEnabled(1);
            $clientGroup->setDeleted(null);
            $entityManager->persist($clientGroup);
            $entityManager->flush();

            $clientGroupAlter[] = $clientGroup->getId();
        }
        $clientGroupsExistent = $client->getClientGroups();
        if ($clientGroupsExistent) {
            foreach ($clientGroupsExistent as $clientGroupExist) {
                if (!in_array($clientGroupExist->getId(), $clientGroupAlter)) {
                    $clientGroupExist->setDeleted(new DateTime());
                    $clientGroupExist->setEnabled(0);
                    $entityManager->persist($clientGroupExist);
                    $entityManager->flush();
                }
            }
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
        $selectGroup = [];
        foreach ($client->getClientGroups() as $gc) {
            if (($gc->getGroup()->getEnabled() && $gc->getEnabled())) {
                $selectGroup[] = $gc->getGroup()->getId();
            }
        }
        $client->selectGroup = $selectGroup;

        $groups = $this->getOptionsGroups();

        return $this->render('client/show.html.twig', [
            "client" => $client,
            "groups" => $groups
        ]);
    }
}
