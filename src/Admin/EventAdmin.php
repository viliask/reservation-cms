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
        $events = new NavigationItem('app.timeline');
        $events->setPosition(10);
        $events->setView(static::TIMELINE_VIEW);

        $module->addChild($events);

        $events = new NavigationItem('app.events');
        $events->setPosition(20);
        $events->setView(static::EVENT_LIST_VIEW);

        $module->addChild($events);

        $events = new NavigationItem('app.rooms');
        $events->setPosition(30);
        $events->setView(static::ROOM_LIST_VIEW);

        $module->addChild($events);

        $events = new NavigationItem('app.promo');
        $events->setPosition(40);
        $events->setView(static::PROMO_LIST_VIEW);

        $module->addChild($events);

        $events = new NavigationItem('app.setting');
        $events->setPosition(50);
        $events->setView(static::SETTINGS_LIST_VIEW);

        $module->addChild($events);

        $navigationItemCollection->add($module);
    }

    public function configureViews(ViewCollection $viewCollection): void
    {
        $locales = $this->webspaceManager->getAllLocales();

        // Configure Event List View
        $viewCollection->add(
            $this->viewBuilderFactory->createViewBuilder(self::TIMELINE_VIEW, '/events/reservations', 'app.timeline')
        );

        $listToolbarActions = [new ToolbarAction('sulu_admin.add'), new ToolbarAction('sulu_admin.delete')];
        $listView = $this->viewBuilderFactory->createListViewBuilder(self::EVENT_LIST_VIEW, '/events/:locale')
            ->setResourceKey(Event::RESOURCE_KEY)
            ->setListKey(self::EVENT_LIST_KEY)
            ->setTitle('app.events')
            ->addListAdapters(['table'])
            ->addLocales($locales)
            ->setDefaultLocale($locales[0])
            ->setAddView(static::EVENT_ADD_FORM_VIEW)
            ->setEditView(static::EVENT_EDIT_FORM_VIEW)
            ->addToolbarActions($listToolbarActions);
        $viewCollection->add($listView);

        // Configure Event Add View
        $addFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(self::EVENT_ADD_FORM_VIEW, '/events/:locale/add')
            ->setResourceKey(Event::RESOURCE_KEY)
            ->setBackView(static::EVENT_LIST_VIEW)
            ->addLocales($locales);
        $viewCollection->add($addFormView);

        $addDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(self::EVENT_ADD_FORM_VIEW . '.details', '/details')
            ->setResourceKey(Event::RESOURCE_KEY)
            ->setFormKey(self::EVENT_FORM_KEY)
            ->setTabTitle('sulu_admin.details')
            ->setEditView(static::EVENT_EDIT_FORM_VIEW)
            ->addToolbarActions([new ToolbarAction('sulu_admin.save')])
            ->setParent(static::EVENT_ADD_FORM_VIEW);
        $viewCollection->add($addDetailsFormView);

        // Configure Event Edit View
        $editFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(static::EVENT_EDIT_FORM_VIEW, '/events/:locale/:id')
            ->setResourceKey(Event::RESOURCE_KEY)
            ->setBackView(static::EVENT_LIST_VIEW)
            ->setTitleProperty('title')
            ->addLocales($locales);
        $viewCollection->add($editFormView);

        $formToolbarActions = [
            new ToolbarAction('sulu_admin.save'),
            new ToolbarAction('sulu_admin.delete'),
            new TogglerToolbarAction(
                'app.enable_event',
                'enabled',
                'enable',
                'disable'
            ),
        ];
        $editDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(static::EVENT_EDIT_FORM_VIEW . '.details', '/details')
            ->setResourceKey(Event::RESOURCE_KEY)
            ->setFormKey(self::EVENT_FORM_KEY)
            ->setTabTitle('sulu_admin.details')
            ->addToolbarActions($formToolbarActions)
            ->setParent(static::EVENT_EDIT_FORM_VIEW);
        $viewCollection->add($editDetailsFormView);

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////// Room /////////////////////////////////////////////////////////////// Room /////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Configure Event List View
        $listToolbarActions = [new ToolbarAction('sulu_admin.add'), new ToolbarAction('sulu_admin.delete')];
        $listView = $this->viewBuilderFactory->createListViewBuilder(self::ROOM_LIST_VIEW, '/rooms/:locale')
            ->setResourceKey(Room::RESOURCE_KEY)
            ->setListKey(self::ROOM_LIST_KEY)
            ->setTitle('app.rooms')
            ->addListAdapters(['table'])
            ->addLocales($locales)
            ->setDefaultLocale($locales[0])
            ->setAddView(static::ROOM_ADD_FORM_VIEW)
            ->setEditView(static::ROOM_EDIT_FORM_VIEW)
            ->addToolbarActions($listToolbarActions);
        $viewCollection->add($listView);

        // Configure Event Add View
        $addFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(self::ROOM_ADD_FORM_VIEW, '/rooms/:locale/add')
            ->setResourceKey(Room::RESOURCE_KEY)
            ->setBackView(static::ROOM_LIST_VIEW)
            ->addLocales($locales);
        $viewCollection->add($addFormView);

        $addDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(self::ROOM_ADD_FORM_VIEW . '.details', '/details')
            ->setResourceKey(Room::RESOURCE_KEY)
            ->setFormKey(self::ROOM_FORM_KEY)
            ->setTabTitle('sulu_admin.details')
            ->setEditView(static::ROOM_EDIT_FORM_VIEW)
            ->addToolbarActions([new ToolbarAction('sulu_admin.save')])
            ->setParent(static::ROOM_ADD_FORM_VIEW);
        $viewCollection->add($addDetailsFormView);

        // Configure Event Edit View
        $editFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(static::ROOM_EDIT_FORM_VIEW, '/rooms/:locale/:id')
            ->setResourceKey(Room::RESOURCE_KEY)
            ->setBackView(static::ROOM_LIST_VIEW)
            ->setTitleProperty('title')
            ->addLocales($locales);
        $viewCollection->add($editFormView);

        $formToolbarActions = [
            new ToolbarAction('sulu_admin.save'),
            new ToolbarAction('sulu_admin.delete'),
            new TogglerToolbarAction(
                'app.enable',
                'enabled',
                'enable',
                'disable'
            ),
        ];
        $editDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(static::ROOM_EDIT_FORM_VIEW . '.details', '/details')
            ->setResourceKey(Room::RESOURCE_KEY)
            ->setFormKey(self::ROOM_FORM_KEY)
            ->setTabTitle('sulu_admin.details')
            ->addToolbarActions($formToolbarActions)
            ->setParent(static::ROOM_EDIT_FORM_VIEW);
        $viewCollection->add($editDetailsFormView);

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////// Promo /////////////////////////////////////////////////////////////// Promo ///////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Configure Event List View
        $listToolbarActions = [new ToolbarAction('sulu_admin.add'), new ToolbarAction('sulu_admin.delete')];
        $listView = $this->viewBuilderFactory->createListViewBuilder(self::PROMO_LIST_VIEW, '/promos/:locale')
            ->setResourceKey(PromoOffer::RESOURCE_KEY)
            ->setListKey(self::PROMO_LIST_KEY)
            ->setTitle('app.promo')
            ->addListAdapters(['table'])
            ->addLocales($locales)
            ->setDefaultLocale($locales[0])
            ->setAddView(static::PROMO_ADD_FORM_VIEW)
            ->setEditView(static::PROMO_EDIT_FORM_VIEW)
            ->addToolbarActions($listToolbarActions);
        $viewCollection->add($listView);

        // Configure Event Add View
        $addFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(self::PROMO_ADD_FORM_VIEW, '/promos/:locale/add')
            ->setResourceKey(PromoOffer::RESOURCE_KEY)
            ->setBackView(static::PROMO_LIST_VIEW)
            ->addLocales($locales);
        $viewCollection->add($addFormView);

        $addDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(self::PROMO_ADD_FORM_VIEW . '.details', '/details')
            ->setResourceKey(PromoOffer::RESOURCE_KEY)
            ->setFormKey(self::PROMO_FORM_KEY)
            ->setTabTitle('sulu_admin.details')
            ->setEditView(static::PROMO_EDIT_FORM_VIEW)
            ->addToolbarActions([new ToolbarAction('sulu_admin.save')])
            ->setParent(static::PROMO_ADD_FORM_VIEW);
        $viewCollection->add($addDetailsFormView);

        // Configure Event Edit View
        $editFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(static::PROMO_EDIT_FORM_VIEW, '/promos/:locale/:id')
            ->setResourceKey(PromoOffer::RESOURCE_KEY)
            ->setBackView(static::PROMO_LIST_VIEW)
            ->setTitleProperty('title')
            ->addLocales($locales);
        $viewCollection->add($editFormView);

        $formToolbarActions = [
            new ToolbarAction('sulu_admin.save'),
            new ToolbarAction('sulu_admin.delete'),
            new TogglerToolbarAction(
                'app.enable',
                'enabled',
                'enable',
                'disable'
            ),
        ];
        $editDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(static::PROMO_EDIT_FORM_VIEW . '.details', '/details')
            ->setResourceKey(PromoOffer::RESOURCE_KEY)
            ->setFormKey(self::PROMO_FORM_KEY)
            ->setTabTitle('sulu_admin.details')
            ->addToolbarActions($formToolbarActions)
            ->setParent(static::PROMO_EDIT_FORM_VIEW);
        $viewCollection->add($editDetailsFormView);

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////// ReservationSettings /////////////////////////////////// ReservationSettings ///////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Configure Event List View
        $listToolbarActions = [new ToolbarAction('sulu_admin.add'), new ToolbarAction('sulu_admin.delete')];
        $listView = $this->viewBuilderFactory->createListViewBuilder(self::SETTINGS_LIST_VIEW, '/settings/:locale')
            ->setResourceKey(ReservationSettings::RESOURCE_KEY)
            ->setListKey(self::SETTINGS_LIST_KEY)
            ->setTitle('app.setting')
            ->addListAdapters(['table'])
            ->addLocales($locales)
            ->setDefaultLocale($locales[0])
            ->setAddView(static::SETTINGS_ADD_FORM_VIEW)
            ->setEditView(static::SETTINGS_EDIT_FORM_VIEW)
            ->addToolbarActions($listToolbarActions);
        $viewCollection->add($listView);

        // Configure Event Add View
        $addFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(self::SETTINGS_ADD_FORM_VIEW, '/settings/:locale/add')
            ->setResourceKey(ReservationSettings::RESOURCE_KEY)
            ->setBackView(static::SETTINGS_LIST_VIEW)
            ->addLocales($locales);
        $viewCollection->add($addFormView);

        $addDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(self::SETTINGS_ADD_FORM_VIEW . '.details', '/details')
            ->setResourceKey(ReservationSettings::RESOURCE_KEY)
            ->setFormKey(self::SETTINGS_FORM_KEY)
            ->setTabTitle('sulu_admin.details')
            ->setEditView(static::SETTINGS_EDIT_FORM_VIEW)
            ->addToolbarActions([new ToolbarAction('sulu_admin.save')])
            ->setParent(static::SETTINGS_ADD_FORM_VIEW);
        $viewCollection->add($addDetailsFormView);

        // Configure Event Edit View
        $editFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(static::SETTINGS_EDIT_FORM_VIEW, '/settings/:locale/:id')
            ->setResourceKey(ReservationSettings::RESOURCE_KEY)
            ->setBackView(static::SETTINGS_LIST_VIEW)
            ->setTitleProperty('title')
            ->addLocales($locales);
        $viewCollection->add($editFormView);

        $formToolbarActions = [
            new ToolbarAction('sulu_admin.save'),
            new ToolbarAction('sulu_admin.delete'),
            new TogglerToolbarAction(
                'app.enable_settings',
                'enabled',
                'enable',
                'disable'
            ),
        ];
        $editDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(static::SETTINGS_EDIT_FORM_VIEW . '.details', '/details')
            ->setResourceKey(ReservationSettings::RESOURCE_KEY)
            ->setFormKey(self::SETTINGS_FORM_KEY)
            ->setTabTitle('sulu_admin.details')
            ->addToolbarActions($formToolbarActions)
            ->setParent(static::SETTINGS_EDIT_FORM_VIEW);
        $viewCollection->add($editDetailsFormView);
    }
}
