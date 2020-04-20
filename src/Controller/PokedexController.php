<?php

namespace App\Controller;

use App\Entity\Pokedex;
use App\Controller\BadgeController;
use App\Repository\PokedexRepository;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PokedexController extends AbstractController
{
	/**
	 * Add pokemon in user's pokedex
	 *
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @param PokemonRepository $pokemonRepository
	 * @param PokedexRepository $pokedexRepository
	 * @param BadgeController $badgeController
	 * @return JsonResponse|null
	 * 
	 * @Route("/pokedex/add", name="pokedex_add")
	 */
	public function addPokedex(Request $request, EntityManagerInterface $entityManager, PokemonRepository $pokemonRepository, PokedexRepository $pokedexRepository, BadgeController $badgeController): ?JsonResponse
	{
		// not working with this bellow method to get post query
		// $postRequest = $request->request->get("user_id");

		$jsonPostRequest = \json_decode($request->getContent());

		$pokemonId = $jsonPostRequest->pokemon_id;
		$pokemon = $pokemonRepository->findOneByPokeId($pokemonId);

		$user = $this->getUser();
		$userId = $user->getId();

		// check if user/pokemon line exist in db
		$pokedexRow = $pokedexRepository->findOneBy(["user" => $user, "pokemon" => $pokemon]);

		// if user have this pokemon, increases the amount
		if (!\is_null($pokedexRow)) {
			$currentQuantity = $pokedexRow->getQuantity();
			$pokedexRow->setQuantity($currentQuantity + 1);
		} else {
			$pokedexRow = new Pokedex;
			$pokedexRow
				->setUser($user)
				->setPokemon($pokemon)
				->setQuantity(1);
		}

		// add pokemon in user's pokedex db
		$entityManager->persist($pokedexRow);
		$entityManager->flush();

		// check if new badge is added 
		$badgeAdded = $badgeController->canAddBadge($pokemon->getDifficulty());

		if (!empty($badgeAdded)) {
			return $this->json($badgeAdded);
		} else {
			return new Response();
		}
	}
}
