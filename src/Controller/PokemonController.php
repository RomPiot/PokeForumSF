<?php

namespace App\Controller;

use App\Repository\BadgeRepository;
use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

		switch ($nbBadge) {
			case '0':
				$listPokemon = $pokemonRepository->findBy(["difficulty" => 1]);
				break;
			case '1':
				$listPokemon = $pokemonRepository->findBy(["difficulty" => 2]);
				break;
			case '2':
				$listPokemon = $pokemonRepository->findBy(["difficulty" => 3]);
				break;
			case '3':
				$listPokemon = $pokemonRepository->findBy(["difficulty" => 4]);
				break;
			case '4':
				$listPokemon = $pokemonRepository->findBy(["difficulty" => 5]);
				break;
			case '5':
				$listPokemon = $pokemonRepository->findBy(["difficulty" => 6]);
				break;
			case '6':
				$listPokemon = $pokemonRepository->findBy(["difficulty" => 7]);
				break;
			case '7':
				$listPokemon = $pokemonRepository->findBy(["difficulty" => 8]);
				break;
			case '8':
				$listPokemon = $pokemonRepository->findBy(["difficulty" => 9]);
				break;
			default:
				$listPokemon = $pokemonRepository->findBy(["difficulty" => 1]);
				break;
		}

		$randomIndex = array_rand($listPokemon);
		$pokemon = $listPokemon[$randomIndex];

		return $this->json($pokemon);
	}
}
