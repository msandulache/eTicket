<?php

namespace App\Repository;

use App\Entity\BookingStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookingStatus>
 *
 * @method BookingStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookingStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookingStatus[]    findAll()
 * @method BookingStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookingStatus::class);
    }

//    /**
//     * @return BookingStatus[] Returns an array of BookingStatus objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BookingStatus
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
