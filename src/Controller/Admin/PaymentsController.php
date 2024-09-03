<?php

namespace App\Controller\Admin;

use App\Entity\Payments;
use App\Form\PaymentsType;
use App\Repository\PaymentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/payments')]
class PaymentsController extends AbstractController
{
    #[Route('/', name: 'app_admin_payments_index', methods: ['GET'])]
    public function index(PaymentsRepository $paymentsRepository): Response
    {
        return $this->render('admin/payments/index.html.twig', [
            'payments' => $paymentsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_payments_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PaymentsRepository $paymentsRepository): Response
    {
        $payment = new Payments();
        $form = $this->createForm(PaymentsType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentsRepository->save($payment, true);

            return $this->redirectToRoute('app_admin_payments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/payments/new.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_payments_show', methods: ['GET'])]
    public function show(Payments $payment): Response
    {
        return $this->render('admin/payments/show.html.twig', [
            'payment' => $payment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_payments_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Payments $payment, PaymentsRepository $paymentsRepository): Response
    {
        $form = $this->createForm(PaymentsType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentsRepository->save($payment, true);

            return $this->redirectToRoute('app_admin_payments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/payments/edit.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_payments_delete', methods: ['POST'])]
    public function delete(Request $request, Payments $payment, PaymentsRepository $paymentsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $payment->getId(), $request->request->get('_token'))) {
            $paymentsRepository->remove($payment, true);
        }

        return $this->redirectToRoute('app_admin_payments_index', [], Response::HTTP_SEE_OTHER);
    }
}
