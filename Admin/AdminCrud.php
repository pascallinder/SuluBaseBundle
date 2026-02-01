<?php

namespace Linderp\SuluBaseBundle\Admin;

use Sulu\Bundle\ActivityBundle\Infrastructure\Sulu\Admin\View\ActivityViewBuilderFactoryInterface;
use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItem;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItemCollection;
use Sulu\Bundle\AdminBundle\Admin\View\TogglerToolbarAction;
use Sulu\Bundle\AdminBundle\Admin\View\ToolbarAction;
use Sulu\Bundle\AdminBundle\Admin\View\ViewBuilderFactoryInterface;
use Sulu\Bundle\AdminBundle\Admin\View\ViewCollection;
use Sulu\Bundle\ReferenceBundle\Infrastructure\Sulu\Admin\View\ReferenceViewBuilderFactoryInterface;
use Sulu\Component\Webspace\Manager\WebspaceManagerInterface;
/**
 * Base CRUD admin abstraction for Sulu.
 *
 * This class provides:
 * - List view
 * - Add form
 * - Edit form
 * - Optional enable/disable toggle
 *
 * Consumers must implement {@see define()} and may override
 * protected hook methods to customize behavior.
 */
abstract class AdminCrud extends Admin implements AdminNavigationItem
{
    private ?AdminCrudConfig $definition = null;

    public function __construct(protected ViewBuilderFactoryInterface $viewBuilderFactory,
                                protected ActivityViewBuilderFactoryInterface $activityViewBuilderFactory,
                                protected ReferenceViewBuilderFactoryInterface $referenceViewBuilderFactory,
                                protected WebspaceManagerInterface $webspaceManager)
    {
    }
    public static abstract function define(): AdminCrudConfig;


    public function configureNavigationItems(NavigationItemCollection $navigationItemCollection): void

    {
        if($this instanceof AdminChild) {
            return;
        }
        $navigationItemCollection->add(static::getNavigationItem());
    }

    public function configureViews(ViewCollection $viewCollection): void
    {
        $locales = $this->webspaceManager->getAllLocales();
        $listView = $this->viewBuilderFactory->createListViewBuilder($this->getDefinition()->list->view, $this->getCollectionRoute())
            ->setResourceKey($this->getDefinition()->resourceKey)
            ->setListKey($this->getDefinition()->list->key)
            ->setTitle($this->getDefinition()->list->title)
            ->addListAdapters($this->getListAdapters())
            ->addLocales($locales)
            ->setDefaultLocale($locales[0])
            ->setAddView($this->getDefinition()->form->addView)
            ->setEditView($this->getDefinition()->form->editView)
            ->addToolbarActions($this->buildListToolbarActions());
        $viewCollection->add($listView);

        $addFormView = $this->viewBuilderFactory->createResourceTabViewBuilder($this->getDefinition()->form->addView, $this->getCollectionRoute() .'/add')
            ->setResourceKey($this->getDefinition()->resourceKey)
            ->setBackView($this->getDefinition()->list->view)
            ->addLocales($locales);
        $viewCollection->add($addFormView);

        $addDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder($this->getDefinition()->form->addView . '.details', '/details')
            ->setResourceKey($this->getDefinition()->resourceKey)
            ->setFormKey($this->getDefinition()->form->key)
            ->setTabTitle('sulu_admin.details')
            ->setEditView($this->getDefinition()->form->editView)
            ->addToolbarActions($this->buildAddToolbarActions())
            ->setParent($this->getDefinition()->form->addView);
        $viewCollection->add($addDetailsFormView);

        $editFormView = $this->viewBuilderFactory->createResourceTabViewBuilder($this->getDefinition()->form->editView, $this->getCollectionRoute().'/:id')
            ->setResourceKey($this->getDefinition()->resourceKey)
            ->setBackView($this->getDefinition()->list->view)
            ->setTitleProperty($this->getDefinition()->form->titleProperty)
            ->addLocales($locales);
        $viewCollection->add($editFormView);

        $editDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder($this->getDefinition()->form->editView . '.details', '/details')
            ->setResourceKey($this->getDefinition()->resourceKey)
            ->setFormKey($this->getDefinition()->form->key)
            ->setTabTitle('sulu_admin.details')
            ->addToolbarActions($this->buildEditToolbarActions())
            ->setParent($this->getDefinition()->form->editView);
        $viewCollection->add($editDetailsFormView);
    }
    /**
     * @return string[]
     */
    protected function getListAdapters(): array
    {
        return ['table'];
    }
    /**
     * @return ToolbarAction[]
     */
    protected function buildListToolbarActions(): array
    {
        return [
            new ToolbarAction('sulu_admin.add'),
            new ToolbarAction('sulu_admin.delete')
        ];

    }
    /**
     * @return ToolbarAction[]
     */
    protected function buildAddToolbarActions(): array
    {
        return [new ToolbarAction('sulu_admin.save')];
    }
    /**
     * @return ToolbarAction[]
     */
    protected function buildEditToolbarActions(): array
    {
        $toolbarActions = [
            new ToolbarAction('sulu_admin.save'),
            new ToolbarAction('sulu_admin.delete'),
        ];
        if ($this->supportsEnableToggle()) {
            $toolbarActions[] = new TogglerToolbarAction(
                $this->getEnableLabel(),
                $this->getEnableProperty(),
                'enable',
                'disable',
            );
        }
        return $toolbarActions;
    }
    protected function getCollectionRoute(): string
    {
        return '/' . $this->getDefinition()->resourceKey . '/:locale';
    }
    /**
     * Indicates whether the admin supports an enable/disable toggle.
     *
     * Override to customize toggle availability without implementing EnableToggleAdmin.
     */
    protected function supportsEnableToggle(): bool
    {
        return $this instanceof AdminEnableToggle;
    }

    final protected function getDefinition(): AdminCrudConfig
    {
        return $this->definition ??= static::define();
    }

    public static function getNavigationItem():NavigationItem{
        $definition = static::define();
        $navigationItem = new NavigationItem($definition->nav->title);
        $navigationItem->setPosition($definition->nav->position);
        $navigationItem->setIcon($definition->nav->icon);
        $navigationItem->setView($definition->list->view);
        return $navigationItem;
    }
}