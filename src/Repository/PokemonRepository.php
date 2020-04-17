<?php

namespace App\Repository;

use App\Entity\Pokemon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Pokemon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pokemon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pokemon[]    findAll()
 * @method Pokemon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Pokemon::class);
	}

	/**
	 * return all Pokemon with max difficulty parameter
	 *
	 * @param int $value
	 * @return [Pokemon]
	 */
	public function findByDifficult(int $difficulty)
	{
		return $this->createQueryBuilder('p')
			->andWhere('p.difficulty <= :val')
			->setParameter('val', $difficulty)
			->orderBy('p.id', 'ASC')
			->getQuery()
			->getResult();
	}


	/**
	 * Return Pokemon from PokeId (nb in real Pokedex)
	 *
	 * @param int $pokeId
	 * @return Pokemon|null
	 */
	public function findOneByPokeId($pokeId): ?Pokemon
	{
		return $this->createQueryBuilder('p')
			->andWhere('p.idPokemon = :val')
			->setParameter('val', $pokeId)
			->getQuery()
			->getOneOrNullResult();
	}
}
