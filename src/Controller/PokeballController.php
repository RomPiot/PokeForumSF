<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PokeballController extends AbstractController
{
	/**
	 * To add a pokeball to all users who have less than 6 
	 * 
	 * @Route("/pokeball/add", name="user_add_pokeball")
	 */
	public function addPokeball(EntityManagerInterface $entityManager)
	{
		$updatePokeball = $entityManager->createQuery('update App\Entity\User u set u.pokeball = u.pokeball + 1 where u.pokeball < 6');

		$updatePokeball->execute();

		return new Response('Pokeball added !');
	}


	/**
	 * To remove a pokeball to one user
	 * 
	 * @Route("/pokeball/remove", name="user_remove_pokeball")
	 */
	public function removePokeball(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager)
	{
		// $tokenReceived = $request->request->get("token");

		// Not working 
		// $userId = $request->request->get("user_id");

		// $jsonPostRequest = \json_decode($request->getContent());
		// $userId = $jsonPostRequest->user_id;

		// $user = $userRepository->find($userId);
		$user = $this->getUser();

		$nbPokeballUser = $user->getPokeball();
		if ($nbPokeballUser > 0) {

			$removePokeball = $nbPokeballUser - 1;

			$user->setPokeball($removePokeball);
			$entityManager->persist($user);
			$entityManager->flush();

			return new Response('Remove a pokeball. Now : ' . $user->getPokeball());
		} else {
			return new Response("User haven't pokeball");
		}
	}
}
