<?php

namespace App\Controller\Website;

use App\Form\Type\ReservationType;
use Sulu\Bundle\WebsiteBundle\Controller\WebsiteController;
use Sulu\Component\Content\Compat\StructureInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HomeController extends WebsiteController
{
    public function indexAction(StructureInterface $structure, $preview = false, $partial = false)
    {
        $response = $this->renderStructure(
            $structure,
            [],
            $preview,
            $partial
        );
        return $response;
    }

    protected function renderStructure(
        StructureInterface $structure,
        $attributes = [],
        $preview = false,
        $partial = false
    ) {
        // extract format twig file
        if (!$preview) {
            $request = $this->getRequest();
            $requestFormat = $request->getRequestFormat();
        } else {
            $requestFormat = 'html';
        }

        $viewTemplate = $structure->getView() . '.' . $requestFormat . '.twig';

        if (!$this->get('twig')->getLoader()->exists($viewTemplate)) {
            throw new HttpException(
                406,
                sprintf('Page does not exist in "%s" format.', $requestFormat)
            );
        }

        $form = $this->createForm(ReservationType::class, null, ['action' => $this->generateUrl('reservation')]);

        $attributes = ['form' => $form->createView()];

        // get attributes to render template
        $data = $this->getAttributes($attributes, $structure, $preview);

        // if partial render only content block else full page
        if ($partial) {
            $content = $this->renderBlock(
                $viewTemplate,
                'content',
                $data
            );
        } elseif ($preview) {
            $content = $this->renderPreview(
                $viewTemplate,
                $data
            );
        } else {
            $content = $this->renderView(
                $viewTemplate,
                $data
            );
        }

        $response = new Response($content);

        if (!$preview && $this->getCacheTimeLifeEnhancer()) {
            $this->getCacheTimeLifeEnhancer()->enhance($response, $structure);
        }

        return $response;
    }

    public function reservation(): Response
    {
        return $this->render('/bookReservation/bookReservation.html.twig');
    }
}
