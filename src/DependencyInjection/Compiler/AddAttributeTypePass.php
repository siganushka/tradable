<?php

namespace App\DependencyInjection\Compiler;

use App\Registry\AttributeTypeRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AddAttributeTypePass implements CompilerPassInterface
{
    const TAG_NAME = 'app.attribute_type';

    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition(AttributeTypeRegistry::class);
        foreach ($container->findTaggedServiceIds(self::TAG_NAME) as $id => $attributes) {
            foreach ($attributes as $attribute) {
                $definition->addMethodCall('register', [new Reference($id)]);
            }
        }
    }
}
