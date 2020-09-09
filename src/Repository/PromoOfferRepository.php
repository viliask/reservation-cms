<?php

namespace App\Repository;

use App\Entity\PromoOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryInterface;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryTrait;

/**
 * @method PromoOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromoOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromoOffer[]    findAll()
 * @method PromoOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromoOfferRepository extends ServiceEntityRepository implements DataProviderRepositoryInterface
{
    use DataProviderRepositoryTrait {
        findByFilters as parentFindByFilters;
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PromoOffer::class);
    }

    public function create(string $locale): PromoOffer
    {
        $promoOffer = new PromoOffer();
        $promoOffer->setLocale($locale);

        return $promoOffer;
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

    public function save(PromoOffer $promoOffer): void
    {
        $this->getEntityManager()->persist($promoOffer);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id, string $locale): ?PromoOffer
    {
        $promoOffer = $this->find($id);
        if (!$promoOffer) {
            return null;
        }

        $promoOffer->setLocale($locale);

        return $promoOffer;
    }

    public function findByFilters($filters, $page, $pageSize, $limit, $locale, $options = [])
    {
        $entities = $this->parentFindByFilters($filters, $page, $pageSize, $limit, $locale, $options);

        return array_map(
            function (PromoOffer $entity) use ($locale) {
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
}
