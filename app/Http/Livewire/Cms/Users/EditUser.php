<?php

namespace App\Http\Livewire\Cms\Users;

use App\Rules\DigitExist;
use App\Rules\LowercaseExist;
use App\Rules\UppercaseExist;

class EditUser extends UserForm
{
    /**
     * Define the current operation of the livewire component.
     * The valid options for operation values are: create, view, update.
     *
     * @var string
     */
    protected string $operation = 'update';

    /**
     * The validation rules for user model.
     *
     * @return array
     */
    protected function rules(): array
    {
        $rules = parent::rules();
        $rules['data.password'] = ['nullable', 'string', 'min:8', new UppercaseExist(), new LowercaseExist(), new DigitExist()];
        $rules['data.password_confirmation'] = 'nullable';
        return $rules;
    }

    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.users.edit_user')
            ->extends('cms::_layouts.app')
            ->section('content');
    }
}
