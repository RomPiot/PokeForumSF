<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\BadgeRepository;
use App\Repository\PokedexRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class BadgeController extends PokeController
{
	private $pokemonController;
	private $badgeRepository;
	private $serializer;
	private $entityManager;

	public function __construct(UserRepository $userRepository, BadgeRepository $badgeRepository, PokedexRepository $pokedexRepository, EntityManagerInterface $entityManager, PokemonController $pokemonController)
	{
		$this->userRepository = 	$userRepository;
		$this->pokemonController = 	$pokemonController;
		$this->badgeRepository = $badgeRepository;
		$this->pokedexRepository = $pokedexRepository;
		$this->entityManager = $entityManager;

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
	 * Return all user's badges
	 * 
	 * @param User $user
	 * @return Response
	 * 
	 * @Route("/badges/{id}", name="badges_user")
	 */
	public function allBadges(User $user): Response
	{
		$allBadge = $this->badgeRepository->findByUser($user);

		$jsonBadges = $this->serializer->serialize($allBadge, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['users']]);

		// For instance, return a Response with encoded Json
		return new Response($jsonBadges, 200, ['Content-Type' => 'application/json']);
	}


	/**
	 * Return the highter user's badge level
	 *
	 * @param User $user
	 * @return integer
	 */
	public function checkMaxBadge(User $user): int
	{
		$badgeMaxLevel = $this->badgeRepository->findHighterByUser($user->getId())["max_level"];

		$badgeMaxLevel = \is_null($badgeMaxLevel) ? 0 : $badgeMaxLevel;

		return $badgeMaxLevel;
	}

	/**
	 * Add user's badge in db
	 *
	 * @param integer $level
	 * @return void
	 */
	public function addBadge(int $level): void
	{
		$newBadge = $this->badgeRepository->findOneByLevel($level);

		$user = $this->getUser();
		$user->addBadge($newBadge);

		$this->entityManager->persist($user);
		$this->entityManager->flush();
	}

	/**
	 * Check if user won a new badge 
	 *
	 * @param integer $pokemonDifficulty
	 * @return Response|null
	 */
	public function canAddBadge(int $pokemonDifficulty): ?Response
	{
		$maxBadge = $this->checkMaxBadge($this->getUser());

		if ($pokemonDifficulty == ($maxBadge + 1)) {
			$canAddBadge = $this->pokemonController->countPokemonByDifficulty($pokemonDifficulty);
		} else {
			$canAddBadge = false;
		}

		if ($canAddBadge == true) {
			$newBadgeLvl = $maxBadge + 1;
			$this->addBadge($newBadgeLvl);

			$newBadge = $this->badgeRepository->find($newBadgeLvl);

			$jsonBadge = $this->serializer->serialize($newBadge, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['users']]);

			// For instance, return a Response with encoded Json
			return new Response($jsonBadge, 200, ['Content-Type' => 'application/json']);
		} else {
			return new Response('', 200, ['Content-Type' => 'application/json']);
		}
	}
}
