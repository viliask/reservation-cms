<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\RoomRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Sulu\Component\Rest\AbstractRestController;
use Sulu\Component\Rest\ListBuilder\Doctrine\DoctrineListBuilderFactoryInterface;
use Sulu\Component\Rest\ListBuilder\Metadata\FieldDescriptorFactoryInterface;
use Sulu\Component\Rest\ListBuilder\PaginatedRepresentation;
use Sulu\Component\Rest\RestHelperInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventController extends AbstractRestController  implements ClassResourceInterface
{
    /**
     * @var EventRepository
     */
    private $repository;

    /**
     * @var RoomRepository
     */
    private $roomRepository;

    /**
     * @var ViewHandlerInterface
     */
    private $viewHandler;

    /**
     * @var FieldDescriptorFactoryInterface
     */
    private $fieldDescriptorFactory;

    /**
     * @var DoctrineListBuilderFactoryInterface
     */
    private $listBuilderFactory;

    /**
     * @var RestHelperInterface
     */
    private $restHelper;

    public function __construct(
        ViewHandlerInterface $viewHandler,
        EventRepository $repository,
        RoomRepository $roomRepository,
        FieldDescriptorFactoryInterface $fieldDescriptorFactory,
        DoctrineListBuilderFactoryInterface $listBuilderFactory,
        RestHelperInterface $restHelper
    ) {
        parent::__construct($viewHandler);
        $this->repository = $repository;
        $this->roomRepository = $roomRepository;
        $this->fieldDescriptorFactory = $fieldDescriptorFactory;
        $this->listBuilderFactory = $listBuilderFactory;
        $this->restHelper = $restHelper;
    }

    public function cgetAction(Request $request): Response
    {
        $fieldDescriptors = $this->fieldDescriptorFactory->getFieldDescriptors(Event::RESOURCE_KEY);
        $listBuilder = $this->listBuilderFactory->create(Event::class);
        $this->restHelper->initializeListBuilder($listBuilder, $fieldDescriptors);
        $listRepresentation = new PaginatedRepresentation(
            $listBuilder->execute(),
            Event::RESOURCE_KEY,
            $listBuilder->getCurrentPage(),
            $listBuilder->getLimit(),
            $listBuilder->count()
        );
        return $this->viewHandler->handle(View::create($listRepresentation));


//        $locale = $request->query->get('locale');
//        $listRepresentation = $this->doctrineListRepresentationFactory->createDoctrineListRepresentation(
//            Event::RESOURCE_KEY,
//            [],
//            ['locale' => $locale]
//        );
//
//        return $this->handleView($this->view($listRepresentation));
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
     * @Rest\Post("/events/{id}")
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
    protected function mapDataToEntity(array $data, Event $entity, $locale): void
    {
        $entity->setTitle($data['title']);

        if ($teaser = $data['teaser'] ?? null) {
            $entity->setTeaser($teaser);
        }

        if ($description = $data['description'] ?? null) {
            $entity->setDescription($description);
        }

        if ($checkIn = $data['checkIn'] ?? null) {
            $entity->setCheckIn(new \DateTimeImmutable($checkIn));
        }

        if ($checkOut = $data['checkOut'] ?? null) {
            $entity->setCheckOut(new \DateTimeImmutable($checkOut));
        }

        $entity->setFirstName($data['firstName']);
        $entity->setLastName($data['lastName']);
        $entity->setPhone($data['phone']);
        $entity->setMail($data['mail']);
        $entity->setGuests((int)$data['guests']);
        $entity->setMessage($data['message']);
        $entity->setStatus($data['status']);
        $entity->setPolicy((bool)$data['policy']);
        $entity->setPrice((float)$data['price']);
        $entity->setLocale($locale);

        if ($rooms = $data['rooms'] ?? null) {
            foreach ($rooms as $room) {
                $entity->addRoom($this->roomRepository->find($room));
            }
        }
    }

    protected function load(int $id, Request $request): ?Event
    {
        return $this->repository->findById($id, $request->query->get('locale'));
    }

    protected function create(Request $request): Event
    {
        return $this->repository->create($request->query->get('locale'));
    }

    protected function save(Event $entity): void
    {
        $this->repository->save($entity);
    }

    protected function remove(int $id): void
    {
        $this->repository->remove($id);
    }
}
