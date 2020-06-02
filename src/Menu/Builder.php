<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;

class Builder
{
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'nav flex-column');

        $menu->addChild('admin.menu.index', ['route' => 'admin_index'])
            ->setExtra('icon', 'fas fa-home');

        $menu->addChild('admin.menu.product', ['route' => 'admin_product'])
            ->setExtra('icon', 'fas fa-gift');

        $menu->addChild('admin.menu.variant', ['route' => 'admin_variant'])
            ->setExtra('icon', 'fas fa-dolly-flatbed');

        $menu->addChild('admin.menu.order', ['route' => 'admin_order'])
            ->setExtra('icon', 'fas fa-shopping-cart');

        $menu->addChild('admin.menu.file', ['route' => 'admin_file'])
            ->setExtra('icon', 'fas fa-cloud-upload-alt');

        foreach ($menu->getChildren() as $child) {
            $child
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link text-truncate')
            ;
        }

        return $menu;
    }
}
