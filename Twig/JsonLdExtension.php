<?php

namespace SecIT\JsonLdBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class JsonLdExtension.
 *
 * @author Tomasz Gemza
 */
class JsonLdExtension extends AbstractExtension implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('json_ld', [$this, 'jsonLdFilter'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'secit_json_ld_extension';
    }

    /**
     * Generate JSON-LD.
     *
     * @param object $object
     *
     * @return string
     */
    public function jsonLdFilter($object)
    {
        return $this->container->get('secit.json_ld')->generate($object);
    }
}
