<?php

namespace App\Http\Livewire\Cms\Admins;

class EditAdmin extends AdminForm
{
    /**
     * Define the current operation of the livewire component.
     * The valid options for operation values are: create, view, update.
     *
     * @var string
     */
    protected string $operation = 'update';

    /**
     * The validation rules for admin model.
     *
     * @var string[]
     */
    protected array $rules = [
        'data.name'                  => 'required|string|min:2|max:255',
        'data.email'                 => 'required|string|email|min:11|max:255',
        'data.password'              => 'nullable|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/',
        'data.password_confirmation' => 'nullable',
    ];

    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.admins.edit_admin')
            ->extends('cms::_layouts.app')
            ->section('content');
    }
}
