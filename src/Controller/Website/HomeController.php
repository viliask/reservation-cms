<?php

namespace App\Controller\Website;

use App\Form\Type\ReservationType;
use Sulu\Bundle\WebsiteBundle\Controller\WebsiteController;
use Sulu\Component\Content\Compat\StructureInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HomeController extends WebsiteController
{
    const RESERVATION_PATH = '/reservation';

    /**
     * @param \Sulu\Component\Content\Compat\StructureInterface $structure
     * @param bool $preview
     * @param bool $partial
     *
     * @return Response
     */
    public function indexAction(StructureInterface $structure, bool $preview = false, bool $partial = false)
    {
        $response = $this->renderStructure(
            $structure,
            [],
            $preview,
            $partial
        );
        return $response;
    }

    /**
     * @param StructureInterface $structure The structure, which has been loaded for rendering
     * @param array $attributes Additional attributes, which will be passed to twig
     * @param bool $preview Defines if the site is rendered in preview mode
     * @param bool $partial Defines if only the content block of the template should be rendered
     *
     * @return Response
     */
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

//      TODO: Make generateUri usable
//        $form = $this->createForm(ReservationType::class, null, ['action' => $this->generateUrl('reservation')]);
        $form = $this->createForm(ReservationType::class, null, ['action' => self::RESERVATION_PATH]);

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
