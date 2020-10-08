<?php

namespace App\Sitemap;

use App\Repository\RoomRepository;
use Sulu\Bundle\WebsiteBundle\Sitemap\Sitemap;
use Sulu\Bundle\WebsiteBundle\Sitemap\SitemapProviderInterface;
use Sulu\Bundle\WebsiteBundle\Sitemap\SitemapUrl;

class SitemapProvider implements SitemapProviderInterface
{
    /**
     * @var RoomRepository
     */
    private $repository;

    /**
     * @param RoomRepository $repository
     */
    public function __construct(RoomRepository $repository)
    {
        $this->repository = $repository;
    }

    public function build($page, $scheme, $host)
    {
        $result[] = new SitemapUrl(
            $scheme . '://' . $host . '/' . 'pokoje',
            'pl',
            'pl',
            null,
            null,
            80
        );
        foreach ($this->repository->findEnabled(50000) as $item) {
            $result[] = new SitemapUrl(
                $scheme . '://' . $host . '/' . $item->getSlug(),
                'pl',
                'pl',
                null,
                null,
                80
            );
        }
        $this->repository->countEnabled();

        return $result;
    }

    public function getAlias()
    {
        return 'rooms';
    }

    public function createSitemap($scheme, $host)
    {
        return new Sitemap($this->getAlias(), $this->getMaxPage($scheme, $host));
    }

    public function getMaxPage($scheme, $host)
    {
        return ceil($this->repository->countEnabled() / self::PAGE_SIZE);
    }
}
