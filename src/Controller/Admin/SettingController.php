<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Common\DoctrineListRepresentationFactory;
use App\Controller\Traits\CommonTrait;
use App\Entity\ReservationSettings;
use App\Repository\ReservationSettingsRepository;
use DateTime;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\ViewHandlerInterface;
use Sulu\Component\Rest\AbstractRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SettingController extends AbstractRestController implements ClassResourceInterface
{
    use CommonTrait;

    /**
     * @var ReservationSettingsRepository
     */
    private $repository;

    /**
     * @var DoctrineListRepresentationFactory
     */
    private $doctrineListRepresentationFactory;

    public function __construct(
        ViewHandlerInterface $viewHandler,
        ReservationSettingsRepository $repository,
        DoctrineListRepresentationFactory $doctrineListRepresentationFactory,
        ?TokenStorageInterface $tokenStorage = null
    ) {
        $this->repository = $repository;
        $this->doctrineListRepresentationFactory = $doctrineListRepresentationFactory;

        parent::__construct($viewHandler, $tokenStorage);
    }

    public function cgetAction(Request $request): Response
    {
        $locale = $request->query->get('locale');
        $listRepresentation = $this->doctrineListRepresentationFactory->createDoctrineListRepresentation(
            ReservationSettings::RESOURCE_KEY,
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
     * @Rest\Post("/settings/{id}")
     */
    public function postTriggerAction(int $id, Request $request): Response
    {
        $reservationSettings = $this->repository->findById($id, $request->query->get('locale'));
        if (!$reservationSettings) {
            throw new NotFoundHttpException();
        }

        $this->repository->save($reservationSettings);

        return $this->handleView($this->view($reservationSettings));
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
    protected function mapDataToEntity(array $data, ReservationSettings $entity, string $locale): void
    {
        $entity->setPriceModifier((int)$data['priceModifier']);
        $entity->setLocale($locale);

        if ($startDate = $data['winterStart'] ?? null) {
            $entity->setWinterStart(new DateTime($startDate));
        }

        if ($endDate = $data['winterEnd'] ?? null) {
            $entity->setWinterEnd(new DateTime($endDate));
        }
    }

    protected function load(int $id, Request $request): ?ReservationSettings
    {
        return $this->repository->findById($id, $request->query->get('locale'));
    }

    protected function create(Request $request): ReservationSettings
    {
        return $this->repository->create($request->query->get('locale'));
    }

    protected function save(ReservationSettings $entity): void
    {
        $this->repository->save($entity);
    }

    protected function remove(int $id): void
    {
        $this->repository->remove($id);
    }
}