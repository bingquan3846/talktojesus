<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GoogleController extends AbstractController
{
    #[Route('/connect/google', name: 'app_connect_google')]
    public function connectAction(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry->getClient('google')->redirect([
            'profile', 'email' // the scopes you want to access
        ], []);
    }

    #[Route('/connect/google/check', name: 'connect_google_check')]
    public function connectCheckAction(Request $requst, ClientRegistry $clientRegistry): Response
    {
        $client = $clientRegistry->getClient('google');
        try {
            // the exact class depends on which provider you're using
            /** @var GoogleUser $user */
            $user = $client->fetchUser();

            $email = $user->getEmail();

            return $this->redirectToRoute('app_register', ['email' => $email]);

        } catch (IdentityProviderException | \Exception $e) {

            return new Response($e->getMessage());
        }
    }
}
