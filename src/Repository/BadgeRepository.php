<?php

namespace App\Repository;

use App\Entity\Badge;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Badge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Badge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Badge[]    findAll()
 * @method Badge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BadgeRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Badge::class);
	}

	/**
	 * @return Badge[] Returns an array of Badge objects for a user
	 */
	public function findByUser($userId)
	{
		return $this->createQueryBuilder('b')
			->leftJoin("b.users", "bu")
			->andWhere('bu.id = :id')
			->setParameter('id', $userId)
			->orderBy('b.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult();
	}

	/**
	 * @return Badge Returns an the highter Badge object for a user
	 */
	public function findHighterByUser($userId)
	{
		return $this->createQueryBuilder('b')
			->select('MAX(b.level) AS max_level')
			->leftJoin("b.users", "bu")
			->andWhere('bu.id = :id')
			->setParameter('id', $userId)
			->orderBy('b.id', 'ASC')
			->setMaxResults(1)
			->getQuery()
            ->getOneOrNullResult();

	}

	/*
    public function findOneBySomeField($value): ?Badge
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
