<?php

namespace SecIT\JsonLdBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class JsonLdTransformerPass.
 *
 * @author Tomasz Gemza
 */
class JsonLdTransformerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('secit.json_ld')) {
            return;
        }

        $definition = $container->findDefinition('secit.json_ld');
        $taggedServices = $container->findTaggedServiceIds('secit.jsonld_transformer');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addTransformer', [$attributes['class'], new Reference($id)]);
            }
        }
    }
}
