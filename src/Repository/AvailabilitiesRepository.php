<?php

namespace App\Repository;

use App\Entity\Availabilities;
use App\Entity\Business;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Availabilities>
 */
class AvailabilitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Availabilities::class);
    }

    /**
     * @return Availabilities[]
     */
    public function search(Business $business, ?\DateTimeInterface $from = null): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.business = :business')
            ->andWhere('a.startDate >= :from')
            ->setParameter('business', $business)
            ->setParameter('from', $from ?? new \DateTime())
            ->orderBy('a.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Availabilities[] Returns an array of Availabilities objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Availabilities
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
