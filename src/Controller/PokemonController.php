<?php

namespace App\Controller;

use App\Repository\BadgeRepository;
use App\Repository\PokemonRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PokemonController extends AbstractController
{
	/**
	 * @Route("/pokemon/random", name="pokemon_random")
	 */
	public function random_pokemon(PokemonRepository $pokemonRepository, BadgeRepository $badgeRepository)
	{
		$user = $this->getUser();
		$nbBadge = count($badgeRepository->findByUser($user->getId()));
		$nbPokeballUser = $user->getPokeball();

		if ($nbPokeballUser > 0) {

			switch ($nbBadge) {
				case '0':
					$listPokemon = $pokemonRepository->findByDifficult(1);
					break;
				case '1':
					$listPokemon = $pokemonRepository->findByDifficult(2);
					break;
				case '2':
					$listPokemon = $pokemonRepository->findByDifficult(3);
					break;
				case '3':
					$listPokemon = $pokemonRepository->findByDifficult(4);
					break;
				case '4':
					$listPokemon = $pokemonRepository->findByDifficult(5);
					break;
				case '5':
					$listPokemon = $pokemonRepository->findByDifficult(6);
					break;
				case '6':
					$listPokemon = $pokemonRepository->findByDifficult(7);
					break;
				case '7':
					$listPokemon = $pokemonRepository->findByDifficult(8);
					break;
				case '8':
					$listPokemon = $pokemonRepository->findByDifficult(9);
					break;
				default:
					$listPokemon = $pokemonRepository->findByDifficult(1);
					break;
			}

			$randomIndex = array_rand($listPokemon);
			$pokemon = $listPokemon[$randomIndex];

			return $this->json($pokemon);
		} else {
			return new Response();
		}
	}

	/**
	 * @Route("/pokemon/hunt", name="pokemon_hunt")
	 */
	public function hunt_pokemon(PokemonRepository $pokemonRepository, BadgeRepository $badgeRepository, UserController $userController, UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager)
	{
		$pokemon = $this->random_pokemon($pokemonRepository, $badgeRepository);
		// $userController->removePokeball($userRepository, $request, $entityManager);

		return $this->json($pokemon);
	}
}
