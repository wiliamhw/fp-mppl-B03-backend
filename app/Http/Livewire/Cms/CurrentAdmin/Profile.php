<?php

namespace App\Http\Livewire\Cms\CurrentAdmin;

use App\Models\Admin;
use Cms\Rules\CurrentPasswordMatched;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Profile extends Component
{
    /**
     * Currently logged in admin model / instance.
     *
     * @var Admin
     */
    public Admin $admin;

    /**
     * Currently logged in admin model's attributes.
     *
     * @var Collection
     */
    public Collection $data;

    /**
     * Provide validation rules for this component.
     *
     * @return array
     */
    protected function getRules(): array
    {
        return [
            'data.name'                  => 'required|min:3',
            'data.current_password'      => [
                'required_with:password',
                'required_with:password_confirmation',
                'nullable',
                new CurrentPasswordMatched(),
            ],
            'data.password'             => [
                'required_with:current_password',
                'required_with:password_confirmation',
                'nullable',
                'min:8',
                'confirmed',
            ],
            'data.password_confirmation' => [
                'required_with:current_password',
                'required_with:password',
                'nullable',
            ],
        ];
    }

    /**
     * Provide the breadcrumb items for the current livewire component.
     *
     * @return array[]
     */
    public function getBreadcrumbItemsProperty(): array
    {
        return [
            [
                'title' => 'Update Profile',
                'url'   => route('cms.current-admin.profile'),
            ],
        ];
    }

    /**
     * Handle the mount event.
     *
     * @throws \ErrorException
     */
    public function mount(): void
    {
        $this->admin = cms_admin();
        $this->data = collect($this->admin->toArray());
    }

    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.current-admin.profile')
            ->extends('cms::_layouts.app')
            ->section('content');
    }

    /**
     * Save the current admin profile.
     */
    public function save(): void
    {
        $validated = $this->validate($this->getRules());
        $data = collect($validated['data']);

        if ($data->get('password') !== null) {
            $data->put('password', Hash::make($data->get('password')));
        }

        $this->admin->fill($data->only($this->admin->getFillable())->toArray());

        if ($this->admin->exists() && $this->admin->isDirty()) {
            $this->admin->save();
        }

        $this->data = collect($this->admin->toArray());

        session()->flash('alertType', 'success');
        session()->flash('alertMessage', 'Your profile has been updated successfully.');
    }
}
