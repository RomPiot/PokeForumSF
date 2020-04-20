<?php

namespace App\Controller;

use App\Repository\BadgeRepository;
use App\Repository\PokedexRepository;
use App\Repository\PokemonRepository;
use App\Controller\PokeballController;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class PokemonController extends AbstractController
{
	private $pokemonRepository;
	private $pokedexRepository;
	private $badgeRepository;
	private $serializer;

	public function __construct(PokemonRepository $pokemonRepository, BadgeRepository $badgeRepository, PokedexRepository $pokedexRepository)
	{
		$this->pokemonRepository = 	$pokemonRepository;
		$this->pokedexRepository = 	$pokedexRepository;
		$this->badgeRepository = $badgeRepository;

		$encoder = new JsonEncoder();
		$defaultContext = [
			AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
				return $object->getName();
			},
		];
		$normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

		$this->serializer = new Serializer([$normalizer], [$encoder]);
	}

	/**
	 * Generate a random pokemon by a level of diffuculty
	 *
	 * @param integer $lvlBadge
	 * @return Response
	 * 
	 * @Route("/pokemon/random", name="pokemon_random")
	 */
	public function randomPokemon($lvlBadge = 8): Response
	{
		switch ($lvlBadge) {
			case '0':
				$listPokemon = $this->pokemonRepository->findByDifficult(1);
				break;
			case '1':
				$listPokemon = $this->pokemonRepository->findByDifficult(2);
				break;
			case '2':
				$listPokemon = $this->pokemonRepository->findByDifficult(3);
				break;
			case '3':
				$listPokemon = $this->pokemonRepository->findByDifficult(4);
				break;
			case '4':
				$listPokemon = $this->pokemonRepository->findByDifficult(5);
				break;
			case '5':
				$listPokemon = $this->pokemonRepository->findByDifficult(6);
				break;
			case '6':
				$listPokemon = $this->pokemonRepository->findByDifficult(7);
				break;
			case '7':
				$listPokemon = $this->pokemonRepository->findByDifficult(8);
				break;
			case '8':
				$listPokemon = $this->pokemonRepository->findByDifficult(9);
				break;
			default:
				$listPokemon = $this->pokemonRepository->findByDifficult(1);
				break;
		}

		$randomIndex = array_rand($listPokemon);
		$pokemon = $listPokemon[$randomIndex];


		// Serialize object in Json
		$jsonPokemon = $this->serializer->serialize($pokemon, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['pokedex']]);

		// For instance, return a Response with encoded Json
		return new Response($jsonPokemon, 200, ['Content-Type' => 'application/json']);
	}

	/**
	 * Hunt a new random pokemon according to badge leel
	 *
	 * @param PokeballController $pokeballController
	 * @return JsonResponse
	 * 
	 * @Route("/pokemon/hunt", name="pokemon_hunt")
	 */
	public function huntPokemon(PokeballController $pokeballController): JsonResponse
	{
		$user = $this->getUser();
		$badgeMaxLevel = $this->badgeRepository->findHighterByUser($user->getId())["max_level"];
		$badgeMaxLevel = \is_null($badgeMaxLevel) ? 0 : $badgeMaxLevel;

		$nbPokeballUser = $user->getPokeball();

		if ($nbPokeballUser > 0) {
			$pokemon = $this->randomPokemon($badgeMaxLevel);
			$pokeballController->removePokeball();
		} else {
			$pokemon = null;
		}

		return $this->json($pokemon);
	}

	/**
	 * Display pokemon's detail
	 *
	 * @param [type] $id
	 * @return Response
	 * 
	 * @Route("/pokemon/{id}", name="pokemon_show")
	 */
	public function show($id): Response
	{
		$pokemon = $this->pokemonRepository->findOneByIdPokemon($id);

		return $this->render('pokemon/index.html.twig', [
			'pokemon' => $pokemon
		]);
	}


	/**
	 * Check if user have 5 different pokemon of badge lvl
	 *
	 * @param integer $pokemonDifficulty
	 * @return boolean
	 */
	public function countPokemonByDifficulty(int $pokemonDifficulty): bool
	{
		// Count the nb of pokemons captured by difficulty for a user
		$countPokemon = $this->pokedexRepository->countPokemonByDifficulty($this->getUser()->getId(), $pokemonDifficulty)[1];

		if ($countPokemon == 5) {
			return true;
		} else {
			return false;
		}
	}
}
