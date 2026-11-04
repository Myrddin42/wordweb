<?php

namespace App\Repository;

use App\Entity\BookSeries;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookSeries>
 */
class BookSeriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookSeries::class);
    }

    /**
     * Removes the given series, and, if requested, also every book in it
     * 
     * @param BookSeries $series      The series to remove
     * @param bool       $removeBooks If true, every book in the given series will also be removed, else they will simply be moved to the virtual "unassigned" series
     */
    public function removeSeries(BookSeries $series, bool $removeBooks = false)
    {
        // Get access to the Entity Manager
        $entityManager = $this->getEntityManager();

        // TODO: Either remove the books, or just remove their connection to this series (moving them into the virtual "unassigned" series)
        if ($removeBooks) {

        } else {

        }

        // Delete the series
        $entityManager->remove($series);
        $entityManager->flush();
    }

//    /**
//     * @return BookSeries[] Returns an array of BookSeries objects
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

//    public function findOneBySomeField($value): ?BookSeries
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
