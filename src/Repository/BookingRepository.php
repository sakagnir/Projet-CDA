<?php

namespace App\Repository;

use App\Entity\Availabilities;
use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    /**
     * @return bool
     */
    public function isAvailabilityTaken(Availabilities $availabilities) : bool {
        return (bool) $this->createQueryBuilder('b')
            ->select('1')
            ->setParameter('availabilites', $availabilities)
            ->where('b.availabilites = :availabilites')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    public function findOneBySomeField($value): ?Booking
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
