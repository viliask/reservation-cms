<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\RoomTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RoomTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomTranslation[]    findAll()
 * @method RoomTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomTranslation::class);
    }
}
