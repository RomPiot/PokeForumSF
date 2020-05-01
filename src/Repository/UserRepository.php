<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     */
	/**
     * Used to upgrade (rehash) the user's password automatically over time.
	 *
	 * @param UserInterface $user
	 * @param string $newEncodedPassword
	 * @return void
	 */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function findTopList()
    {		
		return $this->createQueryBuilder('u')
			->leftJoin('u.badges', 'b')
			->leftJoin('u.pokedex', 'p')
            ->addOrderBy('MAX(b.level)', 'DESC')
			->addOrderBy('count(distinct p.pokemon)', 'DESC')
			->groupBy('u')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
	}
	
	/**
	 * To check the old password with the new
	 *
	 * @param int $userId
	 * @return [result]
	 */
	public function findPassword($userId)
    {		
		return $this->createQueryBuilder('u')
			->select('u.password')
			->andWhere('u.id = :id')
			->setParameter('id', $userId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
