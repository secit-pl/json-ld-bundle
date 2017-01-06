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
     * Transform object to the Schema.org class mapping.
     *
     * @see https://github.com/Torann/json-ld#context-types
     *
     * @param mixed $object
     *
     * @return \SecIT\SchemaOrg\Mapping\Type\Thing
     */
    public function transform($object);
}
