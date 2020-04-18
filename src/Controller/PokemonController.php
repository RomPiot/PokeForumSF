<?php

namespace App\Controller;

use App\Entity\Badge;
use App\Entity\Pokemon;
use App\Repository\UserRepository;
use App\Repository\BadgeRepository;
use App\Repository\PokedexRepository;
use App\Repository\PokemonRepository;
use App\Controller\PokeballController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PokemonController extends AbstractController
{
	private $pokemonRepository;
	private $badgeRepository;
	private $serializer;

	public function __construct(PokemonRepository $pokemonRepository, BadgeRepository $badgeRepository, SerializerInterface $serializer)
	{
		$this->pokemonRepository = 	$pokemonRepository;
		$this->badgeRepository = $badgeRepository;

		$encoder = new JsonEncoder();
		$defaultContext = [
			AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
				return $object->getName();
			},
		];
		$normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

		$this->serializer = new Serializer([$normalizer], [$encoder]);
	}

	/**
	 * @Route("/pokemon/random", name="pokemon_random")
	 */
	public function randomPokemon($nbBadge = 8)
	{
		switch ($nbBadge) {
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


		// Serialize your object in Json
		$jsonPokemon = $this->serializer->serialize($pokemon, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['pokedex']]);

		// For instance, return a Response with encoded Json
		return new Response($jsonPokemon, 200, ['Content-Type' => 'application/json']);
	}

	/**
	 * @Route("/pokemon/hunt", name="pokemon_hunt")
	 */
	public function huntPokemon(PokeballController $pokeballController)
	{
		$user = $this->getUser();
		$countBadge = count($this->badgeRepository->findByUser($user->getId()));

		$nbPokeballUser = $user->getPokeball();

		if ($nbPokeballUser > 0) {
			$pokemon = $this->randomPokemon($countBadge);
			$pokeballController->removePokeball();
		} else {
			$pokemon = null;
		}

		return $this->json($pokemon);
	}


	/**
	 * @Route("/pokemon/{id}", name="pokemon_show")
	 */
	public function show($id)
	{
		$pokemon = $this->pokemonRepository->findOneByIdPokemon($id);

		return $this->render('pokemon/index.html.twig', [
			'pokemon' => $pokemon
		]);
	}
}
