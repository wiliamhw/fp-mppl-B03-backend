<?php

namespace Cms\Exceptions;

use ErrorException;

class InvalidPropertyTypeException extends ErrorException
{
    /**
     * InvalidPropertyTypeException constructor.
     *
     * @param string $class
     * @param string $property
     * @param array  $expectedTypes
     */
    public function __construct(string $class, string $property, array $expectedTypes)
    {
        parent::__construct(sprintf(
            'Property [%s] in class [%s] should be an instance of %s.',
            $property,
            $class,
            implode('|', $expectedTypes)
        ));
    }
}
