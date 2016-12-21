<?php

namespace SecIT\JsonLdBundle\Transformer;

/**
 * Interface TransformerInterface.
 *
 * @author Tomasz Gemza
 */
interface TransformerInterface
{
    /**
     * Get context type.
     *
     * @see https://github.com/Torann/json-ld#context-types
     *
     * @return string
     */
    public function getContextType();

    /**
     * Transform object to the context type data array.
     *
     * @param mixed $object
     *
     * @return array
     */
    public function transform($object);
}
