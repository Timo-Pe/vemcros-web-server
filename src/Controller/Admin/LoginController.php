<?php

namespace App\Controller\Admin;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use phpDocumentor\Reflection\PseudoTypes\False_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    const SCOPES_SOCIAL_NETWORK = [
        "google" => [],
        "github" => [
            "user:email",
            "read:user"
        ]
    ];

    #[Route(path: '/admin/login', name: 'app_admin_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // dd($error);
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_admin_dashboard'); // Remplace 'home' par la route de destination
        }
        return $this->render('admin\login\login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/connect/social/{service}', name: 'app_admin_socialNetwork')]
    public function connectSocialNetwork(ClientRegistry $clientRegistry, $service): RedirectResponse
    {
        $client = $clientRegistry->getClient($service);

        if (!in_array($service, array_keys(self::SCOPES_SOCIAL_NETWORK), true) || $client === null) {

            throw $this->createNotFoundException();
        }

        return  $client->redirect(self::SCOPES_SOCIAL_NETWORK[$service], []);
    }


    #[Route(path: '/admin/logout', name: 'app_admin_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
