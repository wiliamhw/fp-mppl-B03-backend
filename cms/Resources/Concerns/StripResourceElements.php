<?php

namespace Cms\Resources\Concerns;

trait StripResourceElements
{
    /**
     * Strip some elements from the given resource collection.
     *
     * @param array $collection
     * @param array $elements
     *
     * @return array
     */
    protected function stripElementsFromCollection(array $collection, array $elements): array
    {
        return collect($collection)->map(static function ($value) use ($elements) {
            foreach ($elements as $element) {
                if (isset($value[$element])) {
                    unset($value[$element]);
                }
            }

            return $value;
        })->toArray();
    }

    /**
     * Strip some elements from the given resource.
     *
     * @param array $resource
     * @param array $elements
     *
     * @return array
     */
    protected function stripElementsFromResource(array $resource, array $elements): array
    {
        foreach ($elements as $element) {
            if (isset($resource[$element])) {
                unset($resource[$element]);
            }
        }

        return $resource;
    }
}
