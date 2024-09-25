<?php

namespace App\Controller\Admin;

use App\Entity\Clients;
use App\Form\ClientsType;
use App\Repository\ClientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/clients')]
class ClientsController extends AbstractController
{
    #[Route('/', name: 'app_admin_clients_index', methods: ['GET'])]
    public function index(ClientsRepository $clientsRepository): Response
    {

        return $this->render('admin/clients/index.html.twig', [
            'clients' => $clientsRepository->getClientsWithBalance(),
        ]);
    }

    #[Route('/new', name: 'app_admin_clients_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClientsRepository $clientsRepository): Response
    {
        $client = new Clients();
        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientsRepository->save($client, true);

            return $this->redirectToRoute('app_admin_clients_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/clients/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_clients_show', methods: ['GET'])]
    public function show(Clients $client): Response
    {
        return $this->json($client, 200, [], ['groups' => ['admin_client']]);
        // return $this->render('admin/clients/show.html.twig', [
        //     'client' => $client,
        // ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_clients_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Clients $client, ClientsRepository $clientsRepository): Response
    {
        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientsRepository->save($client, true);

            return $this->redirectToRoute('app_admin_clients_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/clients/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_clients_delete', methods: ['POST'])]
    public function delete(Request $request, Clients $client, ClientsRepository $clientsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->request->get('_token'))) {
            $clientsRepository->remove($client, true);
        }

        return $this->redirectToRoute('app_admin_clients_index', [], Response::HTTP_SEE_OTHER);
    }
}
