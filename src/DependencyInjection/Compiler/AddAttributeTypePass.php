<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\DependencyInjection\Compiler;

use App\Registry\AttributeTypeRegistryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AddAttributeTypePass implements CompilerPassInterface
{
    private $attributeTypeTag;

    public function __construct(string $attributeTypeTag = 'app.attribute_type')
    {
        $this->attributeTypeTag = $attributeTypeTag;
    }

    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition(AttributeTypeRegistryInterface::class);
        foreach ($container->findTaggedServiceIds($this->attributeTypeTag) as $id => $attributes) {
            foreach ($attributes as $attribute) {
                if (!isset($attribute['alias'])) {
                    throw new \InvalidArgumentException(sprintf('Service "%s" must define the "alias" attribute on "%s" tags.', $id, $this->attributeTypeTag));
                }

                $definition->addMethodCall('register', [$attribute['alias'], new Reference($id)]);
            }
        }
    }
}
