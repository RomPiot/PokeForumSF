<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends PokeController
{
	/**
	 * The user's login page
	 *
	 * @param AuthenticationUtils $authenticationUtils
	 * @return Response
	 * 
     * @Route("/connexion", name="app_login")
	 */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('account/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

	/**
	 * The user's logout
	 * 
	 * This method can be blank because it will be intercepted by the logout key on the firewall.
	 *
     * @Route("/deconnexion", name="app_logout")
	 */
    public function logout()
    {
    }
}
