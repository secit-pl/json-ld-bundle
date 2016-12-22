<?php

namespace SecIT\JsonLdBundle\DependencyInjection;

use SecIT\JsonLdBundle\Service\JsonLd;

/**
 * Interface JsonLdAwareInterface.
 *
 * @author Tomasz Gemza
 */
interface JsonLdAwareInterface
{
    /**
     * Set JsonLd.
     *
     * @param JsonLd|null $jsonLd
     */
    public function setJsonLd(JsonLd $jsonLd = null);
}
