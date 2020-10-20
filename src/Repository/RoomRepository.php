<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryInterface;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryTrait;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository implements DataProviderRepositoryInterface
{
    use DataProviderRepositoryTrait {
        findByFilters as parentFindByFilters;
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function create(string $locale): Room
    {
        $room = new Room();
        $room->setLocale($locale);

        return $room;
    }

    public function remove(int $id): void
    {
        $this->getEntityManager()->remove(
            $this->getEntityManager()->getReference(
                $this->getClassName(),
                $id
            )
        );
        $this->getEntityManager()->flush();
    }

    public function save(Room $room): void
    {
        $this->getEntityManager()->persist($room);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id, string $locale): ?Room
    {
        $room = $this->find($id);
        if (!$room) {
            return null;
        }

        $room->setLocale($locale);

        return $room;
    }

    public function findByFilters($filters, $page, $pageSize, $limit, $locale, $options = [])
    {
        $entities = $this->parentFindByFilters($filters, $page, $pageSize, $limit, $locale, $options);

        return array_map(
            function (Room $entity) use ($locale) {
                return $entity->setLocale($locale);
            },
            $entities
        );
    }

    /** @return Room[] */
    public function findMinGuests(int $guests)
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->where(':guests <= r.maxGuests')
            ->andWhere(':guests >= (r.maxGuests - r.stepsAmount)')
            ->setParameter('guests', $guests)
            ->getQuery()->execute();
    }

    protected function appendJoins(QueryBuilder $queryBuilder, string $alias, string $locale): void
    {
        $queryBuilder->innerJoin($alias . '.translations', 'translation', Join::WITH, 'translation.locale = :locale');
        $queryBuilder->setParameter('locale', $locale);

        $queryBuilder->andWhere($alias . '.enabled = true');
    }

    /** @return Room[] */
    public function findEnabled(int $limit = 3)
    {
        return $this->createQueryBuilder('r')
            ->where('r.enabled = TRUE')
            ->select('r')
            ->orderBy('r.type', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()->execute();
    }

    public function countEnabled(): int
    {
        return (int)$this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.enabled = TRUE')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /** @return Room[] */
    public function findApartments(int $limit = 1)
    {
        return $this->createQueryBuilder('r')
            ->where('r.enabled = TRUE')
            ->where('r.type = :apartmentType')
            ->select('r')
            ->setMaxResults($limit)
            ->setParameter('apartmentType', Room::APARTMENT_TYPE)
            ->getQuery()->execute();
    }

    /** @return Room[] */
    public function findRooms(int $limit = 10)
    {
        return $this->createQueryBuilder('r')
            ->where('r.enabled = TRUE')
            ->where('r.type = :roomType')
            ->select('r')
            ->setMaxResults($limit)
            ->setParameter('roomType', Room::ROOM_TYPE)
            ->getQuery()->execute();
    }
}
