<?php

namespace App\Controller\Admin;

use App\Entity\UnpaidInvoices;
use App\Form\UnpaidInvoicesType;
use App\Repository\UnpaidInvoicesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/unpaid/invoices')]
class UnpaidInvoicesController extends AbstractController
{
    #[Route('/', name: 'app_admin_unpaid_invoices_index', methods: ['GET'])]
    public function index(UnpaidInvoicesRepository $unpaidInvoicesRepository): Response
    {
        return $this->render('admin/unpaid_invoices/index.html.twig', [
            'unpaid_invoices' => $unpaidInvoicesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_unpaid_invoices_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UnpaidInvoicesRepository $unpaidInvoicesRepository): Response
    {
        $unpaidInvoice = new UnpaidInvoices();
        $form = $this->createForm(UnpaidInvoicesType::class, $unpaidInvoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $unpaidInvoicesRepository->save($unpaidInvoice, true);

            return $this->redirectToRoute('app_admin_unpaid_invoices_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/unpaid_invoices/new.html.twig', [
            'unpaid_invoice' => $unpaidInvoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_unpaid_invoices_show', methods: ['GET'])]
    public function show(UnpaidInvoices $unpaidInvoice): Response
    {
        return $this->render('admin/unpaid_invoices/show.html.twig', [
            'unpaid_invoice' => $unpaidInvoice,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_unpaid_invoices_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UnpaidInvoices $unpaidInvoice, UnpaidInvoicesRepository $unpaidInvoicesRepository): Response
    {
        $form = $this->createForm(UnpaidInvoicesType::class, $unpaidInvoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $unpaidInvoicesRepository->save($unpaidInvoice, true);

            return $this->redirectToRoute('app_admin_unpaid_invoices_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/unpaid_invoices/edit.html.twig', [
            'unpaid_invoice' => $unpaidInvoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_unpaid_invoices_delete', methods: ['POST'])]
    public function delete(Request $request, UnpaidInvoices $unpaidInvoice, UnpaidInvoicesRepository $unpaidInvoicesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $unpaidInvoice->getId(), $request->request->get('_token'))) {
            $unpaidInvoicesRepository->remove($unpaidInvoice, true);
        }

        return $this->redirectToRoute('app_admin_unpaid_invoices_index', [], Response::HTTP_SEE_OTHER);
    }
}
