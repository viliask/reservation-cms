<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryInterface;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryTrait;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository implements DataProviderRepositoryInterface
{
    use DataProviderRepositoryTrait {
        findByFilters as parentFindByFilters;
    }

    private $roomRepository;

    public function __construct(ManagerRegistry $registry, RoomRepository $roomRepository)
    {
        parent::__construct($registry, Event::class);
        $this->roomRepository = $roomRepository;
    }

    public function create(string $locale): Event
    {
        $event = new Event();
        $event->setLocale($locale);

        return $event;
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

    public function save(Event $event): void
    {
        $this->getEntityManager()->persist($event);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id, string $locale): ?Event
    {
        $event = $this->find($id);
        if (!$event) {
            return null;
        }

        $event->setLocale($locale);

        return $event;
    }

    public function findByFilters($filters, $page, $pageSize, $limit, $locale, $options = [])
    {
        $entities = $this->parentFindByFilters($filters, $page, $pageSize, $limit, $locale, $options);

        return array_map(
            function (Event $entity) use ($locale) {
                return $entity->setLocale($locale);
            },
            $entities
        );
    }

    protected function appendJoins(QueryBuilder $queryBuilder, string $alias, string $locale): void
    {
        $queryBuilder->innerJoin($alias . '.translations', 'translation', Join::WITH, 'translation.locale = :locale');
        $queryBuilder->setParameter('locale', $locale);

        $queryBuilder->andWhere($alias . '.enabled = true');
    }

    public function findAvailableRooms(string $checkIn, string $checkOut): array
    {
        $reservedRooms = $this->findReservedRooms($checkIn, $checkOut);
        $rooms         = $this->roomRepository->findAll();

        foreach ($reservedRooms as $reservedRoom) {
            foreach ($rooms as $key => $value) {
                if ($rooms[$key]->getId() === $reservedRoom['id']) {
                    unset($rooms[$key]);
                }
            }
        }

        return $rooms;
    }

    public function findReservedRooms(string $checkIn, string $checkOut): array
    {
        $qb = $this->createQueryBuilder('e')
            ->select('r.id')
            ->innerJoin('e.rooms', 'r')
            ->where(':checkIn >= e.checkIn AND :checkIn <= e.checkOut AND e.checkOut > :checkIn')
            ->orWhere(':checkIn <= e.checkIn AND :checkIn <= e.checkOut AND :checkOut > e.checkIn')
            ->setParameter('checkIn', $checkIn)
            ->setParameter('checkOut', $checkOut);

        return $qb->getQuery()->execute();
    }
}
