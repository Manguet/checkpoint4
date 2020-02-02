<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function findAllBookingByDate()
    {
        $today = new \DateTime('now');

        return $this->createQueryBuilder('b')
            ->select('b')
            ->orderBy('b.atDate', 'ASC')
            ->andWhere('b.atDate > :today')
            ->setParameter(':today', $today)
            ->getQuery()
            ->getResult();
    }
}
