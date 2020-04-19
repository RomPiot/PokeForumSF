<?php

namespace App\Controller;

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
	private $userRepository;
	private $pokemonRepository;
	private $pokedexRepository;
	private $badgeRepository;
	private $serializer;
	private $entityManager;

	public function __construct(UserRepository $userRepository, PokemonRepository $pokemonRepository, BadgeRepository $badgeRepository, PokedexRepository $pokedexRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager)
	{
		$this->userRepository = 	$userRepository;
		$this->pokemonRepository = 	$pokemonRepository;
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
	 * @Route("/badge/max", name="badge_max_user")
	 */
	public function checkMaxBadge()
	{
		$user = $this->getUser();

		// get the highter level badge for a user
		$badgeMaxLevel = $this->badgeRepository->findHighterByUser($user->getId())["max_level"];
		$difficulty = $badgeMaxLevel + 1;

		// Count the nb of pokemons by difficulty of badge for a user
		$count = $this->pokedexRepository->countPokemonByDifficulty($user->getId(), $difficulty)[1];
		// \dd($count);
		if ($count >= 5) {
			$this->addBadge($badgeMaxLevel + 1);
		} else {
			return new Response();
		}
	}


	public function addBadge(int $level)
	{
		$newBadge = $this->badgeRepository->findOneByLevel($level);

		$user = $this->getUser();
		$user->addBadge($newBadge);

		\dd($user);

		// $this->entityManager->persist($user);
		// $this->entityManager->flush();
	}
}
