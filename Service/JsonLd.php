<?php

namespace SecIT\JsonLdBundle\Service;

use JsonLd\Context;
use SecIT\JsonLdBundle\Transformer\TransformerInterface;

/**
 * Class JsonLd.
 *
 * @author Tomasz Gemza
 */
class JsonLd
{
    /**
     * Loaded object transformers.
     *
     * @var array
     */
    protected $transformers = [];

    /**
     * Add class transformer.
     *
     * @param string               $class
     * @param TransformerInterface $transformer
     *
     * @return JsonLd
     *
     * @throws \Exception
     */
    public function addTransformer($class, TransformerInterface $transformer)
    {
        if ($this->hasTransformer($class)) {
            throw new \Exception('Transformer for the class '.$class.' already loaded.');
        }

        $this->transformers[$class] = $transformer;

        return $this;
    }

    /**
     * Generate JSON-LD for the object.
     *
     * @param object $object
     *
     * @return string
     *
     * @throws \Exception
     */
    public function generate($object)
    {
        if (!is_object($object)) {
            throw new \Exception('Expected object, got '.gettype($object).'.');
        }

        $transformer = $this->getTransformer($object);

        return (string) Context::create($transformer->getContextType(), $transformer->transform($object));
    }

    /**
     * Get transformer.
     *
     * @param object|string $element
     *
     * @return TransformerInterface
     *
     * @throws \Exception
     */
    public function getTransformer($element)
    {
        if (!$this->hasTransformer($element)) {
            if (is_object($element)) {
                $class = get_class($element);
            } else {
                $class = $element;
            }

            throw new \Exception('Transformer for the class '.$class.' not loaded.');
        }

        return $this->transformers[$this->getElementClass($element)];
    }

    /**
     * Check if transformer is loaded.
     *
     * @param object|string $element
     *
     * @return bool
     */
    public function hasTransformer($element)
    {
        return isset($this->transformers[$this->getElementClass($element)]);
    }

    /**
     * Get element class name.
     *
     * @param object|string $element
     *
     * @return string
     *
     * @throws \Exception
     */
    protected function getElementClass($element)
    {
        if (is_object($element)) {
            $class = get_class($element);
        } elseif (is_string($element)) {
            $class = $element;
        } else {
            throw new \Exception('Only object or string allowed.');
        }

        return $class;
    }
}
