<?php

namespace SecIT\JsonLdBundle\Service;

use JsonLd\Context;
use SecIT\JsonLdBundle\DependencyInjection\JsonLdAwareInterface;
use SecIT\JsonLdBundle\Transformer\TransformerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class JsonLd.
 *
 * @author Tomasz Gemza
 */
class JsonLd implements ContainerAwareInterface
{
    use ContainerAwareTrait;

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

        if ($transformer instanceof JsonLdAwareInterface) {
            $transformer->setJsonLd($this);
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
        return $this->getElementClass($element) !== false;
    }

    /**
     * Transform object to the JSON-LD data array.
     *
     * @param object $object
     *
     * @return array
     */
    public function transform($object)
    {
        return $this->getTransformer($object)->transform($object);
    }

    /**
     * Try to determine the valid element class name to match the loaded transformable classes.
     *
     * @param object|string $element
     *
     * @return string|bool Returns false on failure.
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

        if (isset($this->transformers[$class])) {
            return $class;
        }

        $reflectionClass = new \ReflectionClass($element);
        while ($class = $reflectionClass->getParentClass()) {
            if (isset($this->transformers[$class->getName()])) {
                return $class->getName();
            }
        }

        return false;
    }
}
