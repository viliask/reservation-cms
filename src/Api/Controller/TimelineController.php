<?php

namespace App\Api\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TimelineController extends AbstractFOSRestController
{

    /** @Rest\Get("/timeline") */
    public function TimelineAction(Request $request): JsonResponse
    {
        $result['page'] = [
            'id'  => 1
        ];

        return $this->json($result);
    }
}
