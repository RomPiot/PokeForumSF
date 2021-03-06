<?php

namespace App\Controller\Game;

use App\Controller\PokeController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PokeballController extends PokeController
{
	/**
	 * Add a pokeball to all users who have less than 6 
	 *
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 */
	public function addPokeball(EntityManagerInterface $entityManager): Response
	{
		$updatePokeball = $entityManager->createQuery('update App\Entity\User u set u.pokeball = u.pokeball + 1 where u.pokeball < 6');

		$updatePokeball->execute();

		return new Response('Pokeball added !');
	}

	/**
	 * Add full pokeball to a user who have less than 6 
	 * 
	 * @param EntityManagerInterface $entityManager
	 * @return RedirectResponse
	 * 
	 * @Route("/pokeball/add/full", name="user_add_full_pokeball")
	 */
	public function addFullPokeball(EntityManagerInterface $entityManager): RedirectResponse
	{
		$user = $this->getUser();

		$updatePokeball = $entityManager->createQuery('update App\Entity\User u set u.pokeball = 6 where u.pokeball < 6 and u.id = :userId')
			->setParameters(["userId" => $user->getId()]);

		$updatePokeball->execute();

		return $this->redirectToRoute("home");
	}

	/**
	 * To remove a pokeball to one user
	 *
	 * @return Response
	 */
	public function removePokeball(): Response
	{
		$entityManager = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		$nbPokeballUser = $user->getPokeball();

		if ($nbPokeballUser > 0) {
			$user->setPokeball($nbPokeballUser - 1);
			$entityManager->persist($user);
			$entityManager->flush();
		}

		return new Response('Pokeball removed');
	}
}
