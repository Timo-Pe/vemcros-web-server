<?php

namespace App\Controller\Admin;

use App\Entity\Accounts;
use App\Form\AccountsType;
use App\Repository\AccountsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/accounts')]
class AccountsController extends AbstractController
{
    #[Route('/', name: 'app_admin_accounts_index', methods: ['GET'])]
    public function index(AccountsRepository $accountsRepository): Response
    {
        return $this->render('admin/accounts/index.html.twig', [
            'accounts' => $accountsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_accounts_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AccountsRepository $accountsRepository): Response
    {
        $account = new Accounts();
        $form = $this->createForm(AccountsType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accountsRepository->save($account, true);

            return $this->redirectToRoute('app_admin_accounts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/accounts/new.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_accounts_show', methods: ['GET'])]
    public function show(Accounts $account): Response
    {
        return $this->render('admin/accounts/show.html.twig', [
            'account' => $account,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_accounts_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Accounts $account, AccountsRepository $accountsRepository): Response
    {
        $form = $this->createForm(AccountsType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accountsRepository->save($account, true);

            return $this->redirectToRoute('app_admin_accounts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/accounts/edit.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_accounts_delete', methods: ['POST'])]
    public function delete(Request $request, Accounts $account, AccountsRepository $accountsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $account->getId(), $request->request->get('_token'))) {
            $accountsRepository->remove($account, true);
        }

        return $this->redirectToRoute('app_admin_accounts_index', [], Response::HTTP_SEE_OTHER);
    }
}
