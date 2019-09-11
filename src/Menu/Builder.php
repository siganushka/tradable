<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

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

        $menu->addChild('admin.menu.welcome', ['route' => 'admin_index'])
            ->setExtra('icon', 'fas fa-home');

        $menu->addChild('admin.menu.attribute', ['route' => 'admin_attribute'])
            ->setExtra('icon', 'fas fa-cubes');

        $menu->addChild('admin.menu.category', ['route' => 'admin_category'])
            ->setExtra('icon', 'fas fa-sitemap');

        $menu->addChild('admin.menu.product', ['route' => 'admin_product'])
            ->setExtra('icon', 'fas fa-gift');

        $menu->addChild('admin.menu.order', ['route' => 'admin_order'])
            ->setExtra('icon', 'fas fa-shopping-cart');

        foreach ($menu->getChildren() as $child) {
            $child
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link text-truncate')
            ;
        }

        return $menu;
    }
}
