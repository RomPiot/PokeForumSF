<?php

namespace App\Repository;

use App\Entity\Pokedex;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Pokedex|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pokedex|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pokedex[]    findAll()
 * @method Pokedex[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokedexRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Pokedex::class);
	}

	/**
	 * @return Pokedex Returns a Pokedex object
	 */
	public function findUserPokemonRow($user, $pokemon): ?Pokedex
	{
		return $this->createQueryBuilder('p')
			->andWhere('p.user = :user')
			->andWhere('p.pokemon = :pokemon')
			->setParameter('user', $user)
			->setParameter('pokemon', $pokemon)
			->orderBy('p.id', 'ASC')
			->getQuery()
			->getOneOrNullResult();
	}

	/**
	 * Return all différent Pokemons of a user by difficulty lvl
	 *
	 */
	// public function findByUserAndDifficulty($user, $difficulty) {
	// 	->andWhere('p.user = :user')
	// 	->andWhere('p.pokemon = :pokemon')
	// 	->setParameter('user', $user)
	// 	->setParameter('pokemon', $pokemon)
	// 	->orderBy('p.id', 'ASC')
	// 	->getQuery()
	// 	->getOneOrNullResult();
	// }


	/*
    public function findOneBySomeField($value): ?Pokedex
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
