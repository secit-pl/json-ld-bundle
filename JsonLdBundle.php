<?php

namespace SecIT\JsonLdBundle;

use SecIT\JsonLdBundle\DependencyInjection\JsonLdTransformerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class JsonLdBundle.
 *
 * @author Tomasz Gemza
 */
class JsonLdBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new JsonLdTransformerPass());
    }
}
