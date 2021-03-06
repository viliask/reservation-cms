<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Common\DoctrineListRepresentationFactory;
use App\Controller\Traits\CommonTrait;
use App\Entity\Room;
use App\Repository\RoomRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\ViewHandlerInterface;
use Sulu\Component\Rest\AbstractRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RoomController extends AbstractRestController implements ClassResourceInterface
{
    use CommonTrait;

    /**
     * @var RoomRepository
     */
    private $repository;

    /**
     * @var DoctrineListRepresentationFactory
     */
    private $doctrineListRepresentationFactory;

    public function __construct(
        ViewHandlerInterface $viewHandler,
        RoomRepository $repository,
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
            Room::RESOURCE_KEY,
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
     * @Rest\Post("/rooms/{id}")
     */
    public function postTriggerAction(int $id, Request $request): Response
    {
        $event = $this->repository->findById($id, $request->query->get('locale'));
        if (!$event) {
            throw new NotFoundHttpException();
        }

        switch ($request->query->get('action')) {
            case 'enable':
                $event->setEnabled(true);
                break;
            case 'disable':
                $event->setEnabled(false);
                break;
        }

        $this->repository->save($event);

        return $this->handleView($this->view($event));
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
    protected function mapDataToEntity(array $data, Room $entity, string $locale): void
    {
        $entity->setName($data['name']);
        $entity->setSlug($this->slugify($data['slug']));
        $entity->setMaxGuests((int)$data['maxGuests']);
        $entity->setBasePrice((int)$data['basePrice']);
        $entity->setStepsAmount((int)$data['stepsAmount']);
        $entity->setStepsDiscount((int)$data['stepsDiscount']);
        $entity->setLocale($locale);
        $entity->setWidgetHeader($data['widgetHeader']);
        $entity->setWidgetText($data['widgetText']);
        $entity->setContent($data['content']);
        $entity->setType($data['type']);

        if ($teaser = $data['teaser'] ?? null) {
            $entity->setTeaser($teaser);
        }

        if ($description = $data['description'] ?? null) {
            $entity->setDescription($description);
        }

        if ($title = $data['title'] ?? null) {
            $entity->setTitle($title);
        }
    }

    protected function load(int $id, Request $request): ?Room
    {
        return $this->repository->findById($id, $request->query->get('locale'));
    }

    protected function create(Request $request): Room
    {
        return $this->repository->create($request->query->get('locale'));
    }

    protected function save(Room $entity): void
    {
        $this->repository->save($entity);
    }

    protected function remove(int $id): void
    {
        $this->repository->remove($id);
    }
}
