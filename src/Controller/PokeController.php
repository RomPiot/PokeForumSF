<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PokeController extends AbstractController
{
	/**
	 * Override the renderView to logout user banned
	 */
	protected function renderView(string $view, array $parameters = []): string
	{
		$user = $this->getUser();

		if (is_object($user) && ($user->getIsBlocked())) {
			$this->addFlash("ban", "Votre compte est banni du site.");
			return $this->redirectToRoute("app_logout");
		}

		if (!$this->container->has('twig')) {
			throw new \LogicException('You can not use the "renderView" method if the Twig Bundle is not available. Try running "composer require symfony/twig-bundle".');
		}

		return $this->container->get('twig')->render($view, $parameters);
	}
}
