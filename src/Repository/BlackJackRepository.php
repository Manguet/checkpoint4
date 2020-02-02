<?php

namespace App\Repository;

use App\Entity\BlackJack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BlackJack|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlackJack|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlackJack[]    findAll()
 * @method BlackJack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlackJackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlackJack::class);
    }

    // /**
    //  * @return BlackJack[] Returns an array of BlackJack objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BlackJack
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
