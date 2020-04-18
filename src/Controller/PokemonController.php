<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Repository\UserRepository;
use App\Repository\BadgeRepository;
use App\Repository\PokedexRepository;
use App\Repository\PokemonRepository;
use App\Controller\PokeballController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PokemonController extends AbstractController
{
	/**
	 * @Route("/pokemon/random", name="pokemon_random")
	 */
	public function randomPokemon(PokemonRepository $pokemonRepository, BadgeRepository $badgeRepository)
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
	public function hunt_pokemon(PokemonRepository $pokemonRepository, BadgeRepository $badgeRepository, PokeballController $pokeballController, EntityManagerInterface $entityManager)
	{
		$pokemon = $this->randomPokemon($pokemonRepository, $badgeRepository);
		$pokeballController->removePokeball();

		return $this->json($pokemon);
	}

	/**
	 * @Route("/pokemon/{id}", name="pokemon_show")
	 */
	public function show($id, PokemonRepository $pokemonRepository, PokedexRepository $pokedexRepository)
	{
		$pokemon = $pokemonRepository->findOneByIdPokemon($id);

		return $this->render('pokemon/index.html.twig', [
			'pokemon' => $pokemon
		]);
	}
}
