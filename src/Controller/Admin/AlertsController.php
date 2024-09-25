<?php

namespace App\Controller\Admin;

use App\Entity\Alerts;
use App\Form\AlertsType;
use App\Repository\AlertsRepository;
use App\Repository\ClientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/alerts')]
class AlertsController extends AbstractController
{
    #[Route('/', name: 'app_admin_alerts_index', methods: ['GET'])]
    public function index(AlertsRepository $alertsRepository, ClientsRepository $clientsRepository): Response
    {
        // dd($clientsRepository->getAllAlertsWithClientInfos());
        return $this->render('admin/alerts/index.html.twig', [
            'clients' => $clientsRepository->getAllAlertsWithClientInfos(),
        ]);
    }

    #[Route('/new', name: 'app_admin_alerts_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AlertsRepository $alertsRepository): Response
    {
        $alert = new Alerts();
        $form = $this->createForm(AlertsType::class, $alert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $alertsRepository->save($alert, true);

            return $this->redirectToRoute('app_admin_alerts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/alerts/new.html.twig', [
            'alert' => $alert,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_alerts_show', methods: ['GET'])]
    public function show(Alerts $alert): Response
    {
        return $this->render('admin/alerts/show.html.twig', [
            'alert' => $alert,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_alerts_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Alerts $alert, AlertsRepository $alertsRepository): Response
    {
        $form = $this->createForm(AlertsType::class, $alert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $alertsRepository->save($alert, true);

            return $this->redirectToRoute('app_admin_alerts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/alerts/edit.html.twig', [
            'alert' => $alert,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_alerts_delete', methods: ['POST'])]
    public function delete(Request $request, Alerts $alert, AlertsRepository $alertsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $alert->getId(), $request->request->get('_token'))) {
            $alertsRepository->remove($alert, true);
        }

        return $this->redirectToRoute('app_admin_alerts_index', [], Response::HTTP_SEE_OTHER);
    }
}
