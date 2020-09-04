<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Common\DoctrineListRepresentationFactory;
use App\Entity\PromoOffer;
use App\Repository\PromoOfferRepository;
use App\Repository\RoomRepository;
use DateTime;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\ViewHandlerInterface;
use Sulu\Component\Rest\AbstractRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PromoController extends AbstractRestController implements ClassResourceInterface
{
    /**
     * @var PromoOfferRepository
     */
    private $repository;

    /**
     * @var RoomRepository
     */
    private $roomRepository;

    /**
     * @var DoctrineListRepresentationFactory
     */
    private $doctrineListRepresentationFactory;

    public function __construct(
        ViewHandlerInterface $viewHandler,
        PromoOfferRepository $repository,
        RoomRepository $roomRepository,
        DoctrineListRepresentationFactory $doctrineListRepresentationFactory,
        ?TokenStorageInterface $tokenStorage = null
    ) {
        $this->repository = $repository;
        $this->roomRepository = $roomRepository;
        $this->doctrineListRepresentationFactory = $doctrineListRepresentationFactory;

        parent::__construct($viewHandler, $tokenStorage);
    }

    public function cgetAction(Request $request): Response
    {
        $locale = $request->query->get('locale');
        $listRepresentation = $this->doctrineListRepresentationFactory->createDoctrineListRepresentation(
            PromoOffer::RESOURCE_KEY,
            [],
            ['locale' => $locale]
        );

        return $this->handleView($this->view($listRepresentation));
    }

    public function getAction(int $id, Request $request): Response
    {
        $entity = $this->load($id, $request);
        if (!$entity) {
            throw new NotFoundHttpException();
        }

        return $this->handleView($this->view($entity));
    }

    public function postAction(Request $request): Response
    {
        $entity = $this->create($request);

        $this->mapDataToEntity($request->request->all(), $entity, $request->query->get('locale'));
        $this->save($entity);

        return $this->handleView($this->view($entity));
    }

    /**
     * @Rest\Post("/promos/{id}")
     */
    public function postTriggerAction(int $id, Request $request): Response
    {
        $promoOffer = $this->repository->findById($id, $request->query->get('locale'));
        if (!$promoOffer) {
            throw new NotFoundHttpException();
        }

        $this->repository->save($promoOffer);

        return $this->handleView($this->view($promoOffer));
    }

    public function putAction(int $id, Request $request): Response
    {
        $entity = $this->load($id, $request);
        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $this->mapDataToEntity($request->request->all(), $entity, $request->query->get('locale'));

        $this->save($entity);

        return $this->handleView($this->view($entity));
    }

    public function deleteAction(int $id): Response
    {
        $this->remove($id);

        return $this->handleView($this->view());
    }

    /**
     * @param string[] $data
     */
    protected function mapDataToEntity(array $data, PromoOffer $entity, string $locale): void
    {
        $entity->setName($data['name']);
        $entity->setDiscount((int)$data['discount']);
        $entity->setMinDays((int)$data['minDays']);
        $entity->setLocale($locale);

        if ($startDate = $data['startDate'] ?? null) {
            $entity->setStartDate(new DateTime($startDate));
        }

        if ($endDate = $data['endDate'] ?? null) {
            $entity->setEndDate(new DateTime($endDate));
        }
    }

    protected function load(int $id, Request $request): ?PromoOffer
    {
        return $this->repository->findById($id, $request->query->get('locale'));
    }

    protected function create(Request $request): PromoOffer
    {
        return $this->repository->create($request->query->get('locale'));
    }

    protected function save(PromoOffer $entity): void
    {
        $this->repository->save($entity);
    }

    protected function remove(int $id): void
    {
        $this->repository->remove($id);
    }
}
