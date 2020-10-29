<?php

namespace App\Controller\Website;

use App\Controller\Traits\CommonTrait;
use App\Entity\Room;
use App\Repository\RoomRepository;
use ONGR\ElasticsearchBundle\Service\Manager;
use ONGR\ElasticsearchBundle\Service\Repository;
use ONGR\ElasticsearchDSL\Sort\FieldSort;
use Sulu\Bundle\ArticleBundle\Document\ArticleViewDocument;
use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;
use Sulu\Bundle\WebsiteBundle\Controller\WebsiteController;
use Sulu\Component\Content\Compat\StructureInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleTaxonomyController extends WebsiteController
{
    use CommonTrait;

    const PAGE_SIZE = 12;

    /**
     * @var Manager
     */
    private $esManagerLive;

    public function __construct(Manager $esManagerLive)
    {
        $this->esManagerLive = $esManagerLive;
    }

    public function indexAction(Request $request, RoomRepository $roomRepository, MediaManagerInterface $mediaManager, StructureInterface $structure, $preview = false, $partial = false)
    {
        $page = $request->query->getInt('page', 1);
        if ($page < 1) {
            throw new NotFoundHttpException();
        }

        $articles = $this->loadArticles($page, self::PAGE_SIZE, $request->getLocale());

        $pages = (int) ceil($articles->count() / self::PAGE_SIZE) ?: 1;

        $media         = [];
        $featuredRooms = $roomRepository->findEnabled();

        /* @var $room Room */
        foreach ($featuredRooms as $room) {
            $media[$room->getId()] = $this->getMedia($room, $mediaManager);
        }

        return $this->renderStructure(
            $structure,
            [
                'page' => $page,
                'pages' => $pages,
                'articles' => $articles,
                'rooms' => $featuredRooms,
                'media' => $media
            ],
            $preview,
            $partial
        );
    }

    private function loadArticles($page, $pageSize, $locale)
    {
        $repository = $this->getRepository();
        $search = $repository->createSearch()
            ->addSort(new FieldSort('authored', FieldSort::DESC))
            ->setFrom(($page - 1) * $pageSize)
            ->setSize($pageSize);

        return $repository->findDocuments($search);
    }

    /**
     * @return Repository
     */
    private function getRepository()
    {
        return $this->esManagerLive->getRepository(ArticleViewDocument::class);
    }
}
