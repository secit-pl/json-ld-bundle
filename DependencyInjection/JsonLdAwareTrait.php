<?php

namespace SecIT\JsonLdBundle\DependencyInjection;

use SecIT\JsonLdBundle\Service\JsonLd;

/**
 * Trait JsonLdAwareTrait.
 *
 * @author Tomasz Gemza
 */
trait JsonLdAwareTrait
{
    /**
     * @var JsonLd|null
     */
    private $jsonLd;

    /**
     * Set JsonLd.
     *
     * @param JsonLd|null $jsonLd
     */
    public function setJsonLd(JsonLd $jsonLd = null)
    {
        $this->jsonLd = $jsonLd;
    }

    /**
     * Get JsonLd.
     *
     * @return JsonLd|null
     */
    public function getJsonLd()
    {
        return $this->jsonLd;
    }
}
