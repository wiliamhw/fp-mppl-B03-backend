<?php

namespace App\Http\Livewire\Cms\Settings;

class EditSetting extends SettingForm
{
    /**
     * Define the current operation of the livewire component.
     * The valid options for operation values are: create, view, update.
     *
     * @var string
     */
    protected string $operation = 'update';

    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.settings.edit_setting')
            ->extends('cms::_layouts.app')
            ->section('content');
    }
}
