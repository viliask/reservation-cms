<?php

namespace App\Repository;

use App\Entity\ReservationSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryTrait;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

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

    use DataProviderRepositoryTrait {
        findByFilters as parentFindByFilters;
    }

    public function create(string $locale): ReservationSettings
    {
        $reservationSettings = new ReservationSettings();
        $reservationSettings->setLocale($locale);

        return $reservationSettings;
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

    public function save(ReservationSettings $promoOffer): void
    {
        $this->getEntityManager()->persist($promoOffer);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id, string $locale): ?ReservationSettings
    {
        $reservationSettings = $this->find($id);
        if (!$reservationSettings) {
            return null;
        }

        $reservationSettings->setLocale($locale);

        return $reservationSettings;
    }

    public function findByFilters($filters, $page, $pageSize, $limit, $locale, $options = [])
    {
        $entities = $this->parentFindByFilters($filters, $page, $pageSize, $limit, $locale, $options);

        return array_map(
            function (ReservationSettings $entity) use ($locale) {
                return $entity->setLocale($locale);
            },
            $entities
        );
    }

    /** @return ReservationSettings[]|null */
    public function isEnabled()
    {
        return $this->createQueryBuilder('rs')
            ->select('rs')
            ->where('rs.enabled = TRUE')
            ->getQuery()->execute();
    }

    protected function appendJoins(QueryBuilder $queryBuilder, string $alias, string $locale): void
    {
        $queryBuilder->innerJoin($alias . '.translations', 'translation', Join::WITH, 'translation.locale = :locale');
        $queryBuilder->setParameter('locale', $locale);

        $queryBuilder->andWhere($alias . '.enabled = true');
    }
}
