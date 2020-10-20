<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Event;
use App\Entity\PromoOffer;
use App\Entity\ReservationSettings;
use App\Entity\Room;
use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItem;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItemCollection;
use Sulu\Bundle\AdminBundle\Admin\View\TogglerToolbarAction;
use Sulu\Bundle\AdminBundle\Admin\View\ToolbarAction;
use Sulu\Bundle\AdminBundle\Admin\View\ViewBuilderFactoryInterface;
use Sulu\Bundle\AdminBundle\Admin\View\ViewCollection;
use Sulu\Component\Webspace\Manager\WebspaceManagerInterface;

class EventAdmin extends Admin
{
    const EVENT_LIST_KEY = 'events';

    const EVENT_FORM_KEY = 'event_details';

    const EVENT_LIST_VIEW = 'app.events_list';

    const EVENT_ADD_FORM_VIEW = 'app.event_add_form';

    const EVENT_EDIT_FORM_VIEW = 'app.event_edit_form';

//////// Timeline

    const TIMELINE_VIEW = 'app.timeline';

//////// Room

    const ROOM_LIST_KEY = 'rooms';

    const ROOM_FORM_KEY = 'room_details';

    const ROOM_LIST_VIEW = 'app.rooms_list';

    const ROOM_ADD_FORM_VIEW = 'app.room_add_form';

    const ROOM_EDIT_FORM_VIEW = 'app.room_edit_form';

//////// PromoOffer

    const PROMO_LIST_KEY = 'promos';

    const PROMO_FORM_KEY = 'promo_details';

    const PROMO_LIST_VIEW = 'app.promos_list';

    const PROMO_ADD_FORM_VIEW = 'app.promo_add_form';

    const PROMO_EDIT_FORM_VIEW = 'app.promo_edit_form';

//////// ReservationSettings

    const SETTINGS_LIST_KEY = 'settings';

    const SETTINGS_FORM_KEY = 'setting_details';

    const SETTINGS_LIST_VIEW = 'app.settings_list';

    const SETTINGS_ADD_FORM_VIEW = 'app.setting_add_form';

    const SETTINGS_EDIT_FORM_VIEW = 'app.setting_edit_form';


    /**
     * @var ViewBuilderFactoryInterface
     */
    private $viewBuilderFactory;

    /**
     * @var WebspaceManagerInterface
     */
    private $webspaceManager;

    public function __construct(
        ViewBuilderFactoryInterface $viewBuilderFactory,
        WebspaceManagerInterface $webspaceManager
    ) {
        $this->viewBuilderFactory = $viewBuilderFactory;
        $this->webspaceManager = $webspaceManager;
    }

    public function configureNavigationItems(NavigationItemCollection $navigationItemCollection): void
    {
        $module = new NavigationItem('app.events');
        $module->setPosition(40);
        $module->setIcon('fa-calendar');

        // Configure a NavigationItem with a View
        $module->addChild($this->navigationItemHelper('app.timeline', 10, static::TIMELINE_VIEW));
        $module->addChild($this->navigationItemHelper('app.events', 20, static::EVENT_LIST_VIEW));
        $module->addChild($this->navigationItemHelper('app.rooms', 30, static::ROOM_LIST_VIEW));
        $module->addChild($this->navigationItemHelper('app.promo', 40, static::PROMO_LIST_VIEW));
        $module->addChild($this->navigationItemHelper('app.setting', 50, static::SETTINGS_LIST_VIEW));

        $navigationItemCollection->add($module);
    }

    public function configureViews(ViewCollection $viewCollection): void
    {
        $locales = $this->webspaceManager->getAllLocales();

        // Configure Timeline List View
        $viewCollection->add(
            $this->viewBuilderFactory->createViewBuilder(self::TIMELINE_VIEW, '/events/reservations', 'app.timeline')
        );

        $this->viewHelper(
            $viewCollection,
            $locales,
            self::EVENT_LIST_VIEW,
            '/events/:locale',
            Event::RESOURCE_KEY,
            self::EVENT_LIST_KEY,
            'app.events',
            static::EVENT_ADD_FORM_VIEW,
            static::EVENT_EDIT_FORM_VIEW,
            self::EVENT_FORM_KEY,
            'app.enable_event'
        );

        //////////////////// Room /////////////////////////////////////////////////////////////// Room /////////////////

        $this->viewHelper(
            $viewCollection,
            $locales,
            self::ROOM_LIST_VIEW,
            '/rooms/:locale',
            Room::RESOURCE_KEY,
            self::ROOM_LIST_KEY,
            'app.rooms',
            static::ROOM_ADD_FORM_VIEW,
            static::ROOM_EDIT_FORM_VIEW,
            self::ROOM_FORM_KEY,
            'app.enable'
        );

        //////////////////// Promo /////////////////////////////////////////////////////////////// Promo ///////////////

        $this->viewHelper(
            $viewCollection,
            $locales,
            self::PROMO_LIST_VIEW,
            '/promos/:locale',
            PromoOffer::RESOURCE_KEY,
            self::PROMO_LIST_KEY,
            'app.promo',
            static::PROMO_ADD_FORM_VIEW,
            static::PROMO_EDIT_FORM_VIEW,
            self::PROMO_FORM_KEY,
            'app.enable'
        );

        //////////////////// ReservationSettings /////////////////////////////////// ReservationSettings ///////////////

        $this->viewHelper(
            $viewCollection,
            $locales,
            self::SETTINGS_LIST_VIEW,
            '/settings/:locale',
            ReservationSettings::RESOURCE_KEY,
            self::SETTINGS_LIST_KEY,
            'app.setting',
            static::SETTINGS_ADD_FORM_VIEW,
            static::SETTINGS_EDIT_FORM_VIEW,
            self::SETTINGS_FORM_KEY,
            'app.enable_settings'
        );
    }

    private function viewHelper(
        ViewCollection $viewCollection,
        array $locales,
        string $listViewString,
        string $path,
        string $resourceKey,
        string $listKey,
        string $title,
        string $addFormViewString,
        string $editFormViewString,
        string $formKey,
        string $label
    ): void {
        $listToolbarActions = [new ToolbarAction('sulu_admin.add'), new ToolbarAction('sulu_admin.delete')];

        // Configure List View
        $listView = $this->viewBuilderFactory->createListViewBuilder($listViewString, $path)
            ->setResourceKey($resourceKey)
            ->setListKey($listKey)
            ->setTitle($title)
            ->addListAdapters(['table'])
            ->addLocales($locales)
            ->setDefaultLocale($locales[0])
            ->setAddView($addFormViewString)
            ->setEditView($editFormViewString)
            ->addToolbarActions($listToolbarActions);
        $viewCollection->add($listView);

        // Configure Add View
        $addFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(
            $addFormViewString,
            $path.'/add'
        )
            ->setResourceKey($resourceKey)
            ->setBackView($listViewString)
            ->addLocales($locales);
        $viewCollection->add($addFormView);

        $addDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(
            $addFormViewString.'.details',
            '/details'
        )
            ->setResourceKey($resourceKey)
            ->setFormKey($formKey)
            ->setTabTitle('sulu_admin.details')
            ->setEditView($editFormViewString)
            ->addToolbarActions([new ToolbarAction('sulu_admin.save')])
            ->setParent($addFormViewString);
        $viewCollection->add($addDetailsFormView);

        // Configure Edit View
        $editFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(
            $editFormViewString,
            $path.'/:id'
        )
            ->setResourceKey($resourceKey)
            ->setBackView($listViewString)
            ->setTitleProperty('title')
            ->addLocales($locales);
        $viewCollection->add($editFormView);

        $formToolbarActions = [
            new ToolbarAction('sulu_admin.save'),
            new ToolbarAction('sulu_admin.delete'),
            new TogglerToolbarAction(
                $label,
                'enabled',
                'enable',
                'disable'
            ),
        ];
        $editDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(
            $editFormViewString.'.details',
            '/details'
        )
            ->setResourceKey($resourceKey)
            ->setFormKey($formKey)
            ->setTabTitle('sulu_admin.details')
            ->addToolbarActions($formToolbarActions)
            ->setParent($editFormViewString);
        $viewCollection->add($editDetailsFormView);
    }

    private function navigationItemHelper(string $name, int $position, string $view): NavigationItem
    {
        $events = new NavigationItem($name);
        $events->setPosition($position);
        $events->setView($view);

        return $events;
    }
}
