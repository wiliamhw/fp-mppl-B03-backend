<?php

namespace Cms\Livewire;

use Livewire\Component;

class ComponentDataRepository
{
    /**
     * Save the livewire component state to the session store.
     *
     * @param mixed $component
     */
    public function save($component): void
    {
        if (is_object($component) && is_subclass_of($component, Component::class)) {
            $data = get_object_vars($component);

            session()->put(get_class($component), $data);
        }
    }

    /**
     * Load the livewire component state from the session store.
     *
     * @param mixed $component
     */
    public function load($component): void
    {
        if (is_object($component) && is_subclass_of($component, Component::class)) {
            $data = session()->get(get_class($component));

            if (($data !== null) && is_array($data)) {
                foreach ($data as $key => $value) {
                    if ($key !== 'id') {
                        $component->$key = $value;
                    }
                }
            }
        }
    }
}
