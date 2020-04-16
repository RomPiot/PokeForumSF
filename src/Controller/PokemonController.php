<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PokemonController extends AbstractController
{
    /**
     * @Route("/pokemon/random", name="pokemon_random")
     */
    public function random_pokemon(PokemonRepository $pokemonRepository)
    {
		$user = $this->getUser();
		dump($user->getBadges());
		die;
		// if ($user->a) {
		// 	# code...
		// }

		$random_id = \random_int(1, 151);
		dump($random_id);
		
		$pokemon = $pokemonRepository->findBy(['idPokemon' => $random_id]);


        return $this->json($pokemon);
    }
}
