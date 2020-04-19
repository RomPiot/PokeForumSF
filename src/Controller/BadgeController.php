<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\BadgeRepository;
use App\Repository\PokedexRepository;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BadgeController extends AbstractController
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
	 * @Route("/badges/{id}", name="badges_user")
	 */
	public function allBadges(User $user)
	{
		$allBadge = $this->badgeRepository->findByUser($user);

		$jsonBadges = $this->serializer->serialize($allBadge, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['users']]);

		// For instance, return a Response with encoded Json
		return new Response($jsonBadges, 200, ['Content-Type' => 'application/json']);
	}


	/**
	 * 
	 */
	public function checkMaxBadge(User $user)
	{
		// get the highter level badge for a user
		$badgeMaxLevel = $this->badgeRepository->findHighterByUser($user->getId())["max_level"];

		$badgeMaxLevel = \is_null($badgeMaxLevel) ? 0 : $badgeMaxLevel;

		return $badgeMaxLevel;
	}


	public function addBadge(int $level)
	{
		$newBadge = $this->badgeRepository->findOneByLevel($level);

		$user = $this->getUser();
		$user->addBadge($newBadge);

		$this->entityManager->persist($user);
		$this->entityManager->flush();
	}

	public function canAddBadge(int $pokemonDifficulty)
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
		}
	}
}
