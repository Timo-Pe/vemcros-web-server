<?php

namespace App\Controller\Admin;

use App\Entity\Credits;
use App\Form\CreditsType;
use App\Repository\CreditsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/credits')]
class CreditsController extends AbstractController
{
    #[Route('/', name: 'app_admin_credits_index', methods: ['GET'])]
    public function index(CreditsRepository $creditsRepository): Response
    {
        return $this->render('admin/credits/index.html.twig', [
            'credits' => $creditsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_credits_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CreditsRepository $creditsRepository): Response
    {
        $credit = new Credits();
        $form = $this->createForm(CreditsType::class, $credit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $creditsRepository->save($credit, true);

            return $this->redirectToRoute('app_admin_credits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/credits/new.html.twig', [
            'credit' => $credit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_credits_show', methods: ['GET'])]
    public function show(Credits $credit): Response
    {
        return $this->render('admin/credits/show.html.twig', [
            'credit' => $credit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_credits_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Credits $credit, CreditsRepository $creditsRepository): Response
    {
        $form = $this->createForm(CreditsType::class, $credit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $creditsRepository->save($credit, true);

            return $this->redirectToRoute('app_admin_credits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/credits/edit.html.twig', [
            'credit' => $credit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_credits_delete', methods: ['POST'])]
    public function delete(Request $request, Credits $credit, CreditsRepository $creditsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $credit->getId(), $request->request->get('_token'))) {
            $creditsRepository->remove($credit, true);
        }

        return $this->redirectToRoute('app_admin_credits_index', [], Response::HTTP_SEE_OTHER);
    }
}
