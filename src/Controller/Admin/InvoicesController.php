<?php

namespace App\Controller\Admin;

use App\Entity\Invoices;
use App\Form\InvoicesType;
use App\Repository\InvoicesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/invoices')]
class InvoicesController extends AbstractController
{
    #[Route('/', name: 'app_admin_invoices_index', methods: ['GET'])]
    public function index(InvoicesRepository $invoicesRepository): Response
    {
        return $this->render('admin/invoices/index.html.twig', [
            'invoices' => $invoicesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_invoices_new', methods: ['GET', 'POST'])]
    public function new(Request $request, InvoicesRepository $invoicesRepository): Response
    {
        $invoice = new Invoices();
        $form = $this->createForm(InvoicesType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invoicesRepository->save($invoice, true);

            return $this->redirectToRoute('app_admin_invoices_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/invoices/new.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_invoices_show', methods: ['GET'])]
    public function show(Invoices $invoice): Response
    {
        return $this->render('admin/invoices/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_invoices_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invoices $invoice, InvoicesRepository $invoicesRepository): Response
    {
        $form = $this->createForm(InvoicesType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invoicesRepository->save($invoice, true);

            return $this->redirectToRoute('app_admin_invoices_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/invoices/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_invoices_delete', methods: ['POST'])]
    public function delete(Request $request, Invoices $invoice, InvoicesRepository $invoicesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $invoice->getId(), $request->request->get('_token'))) {
            $invoicesRepository->remove($invoice, true);
        }

        return $this->redirectToRoute('app_admin_invoices_index', [], Response::HTTP_SEE_OTHER);
    }
}
