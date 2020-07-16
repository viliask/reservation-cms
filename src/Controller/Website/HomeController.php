<?php

namespace App\Controller\Website;

use App\Form\Type\ReservationType;
use Sulu\Bundle\WebsiteBundle\Controller\WebsiteController;
use Sulu\Component\Content\Compat\StructureInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HomeController extends WebsiteController
{
    const RESERVATION_PATH = 'reservation';

    public function indexAction(StructureInterface $structure, bool $preview = false, bool $partial = false): Response
    {
        $form = $this->createForm(ReservationType::class, null, ['action' => $this->generateUrl(self::RESERVATION_PATH)]);
        $attributes = [];
        $attributes['form'] = $form->createView();

        $response = $this->renderStructure(
            $structure,
            $attributes,
            $preview,
            $partial
        );
        return $response;
    }

    public function reservation(): Response
    {
        return $this->render('/bookReservation/bookReservation.html.twig');
    }
}
