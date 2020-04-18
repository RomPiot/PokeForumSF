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
	 */
	public function addPokeball(EntityManagerInterface $entityManager)
	{
		$updatePokeball = $entityManager->createQuery('update App\Entity\User u set u.pokeball = u.pokeball + 1 where u.pokeball < 6');

		$updatePokeball->execute();

		return new Response('Pokeball added !');
	}

	/**
	 * To add full pokeball to all users who have less than 6 
	 * 
	 * @Route("/pokeball/addFull", name="user_add_full_pokeball")
	 */
	public function addFullPokeball(EntityManagerInterface $entityManager)
	{
		$updatePokeball = $entityManager->createQuery('update App\Entity\User u set u.pokeball = 6 where u.pokeball < 6');

		$updatePokeball->execute();

		return new Response('Pokeball full !');
	}


	/**
	 * To remove a pokeball to one user
	 */
	public function removePokeball()
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
