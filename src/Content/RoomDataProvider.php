<?php

declare(strict_types=1);

namespace App\Content;

use Sulu\Component\SmartContent\Orm\BaseDataProvider;

class RoomDataProvider extends BaseDataProvider
{
    public function getConfiguration()
    {
        if (null === $this->configuration) {
            $this->configuration = self::createConfigurationBuilder()
                ->enableLimit()
                ->enablePagination()
                ->enableSorting(
                    [
                        ['column' => 'room_translation.title', 'title' => 'sulu_admin.title'],
                    ]
                )
                ->getConfiguration();
        }

        return parent::getConfiguration();
    }

    protected function decorateDataItems(array $data)
    {
        return array_map(
            function ($item) {
                return new RoomDataItem($item);
            },
            $data
        );
    }
}
