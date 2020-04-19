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
	 * Return all différent Pokemons of a user by difficulty lvl
	 */
	public function countPokemonByDifficulty($user, $difficulty)
	{
		return $this->createQueryBuilder('p')
			->select('COUNT(DISTINCT p.pokemon)')
			->leftJoin('p.pokemon', 'pkm')
			->andWhere('p.user = :user')
			->andWhere('pkm.difficulty = :diff')
			->setParameter('user', $user)
			->setParameter('diff', $difficulty)
			->setMaxResults(1)
			->getQuery()
			->getOneOrNullResult();
	}

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
