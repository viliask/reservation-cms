<?php

namespace App\Repository;

use App\Entity\ReservationSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReservationSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationSettings[]    findAll()
 * @method ReservationSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationSettings::class);
    }

    // /**
    //  * @return ReservationSettings[] Returns an array of ReservationSettings objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReservationSettings
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
