<?php

namespace App\Http\Livewire\Cms\Webinars;

use Carbon\Carbon;
use Illuminate\Validation\Rule;

class EditWebinar extends WebinarForm
{
    /**
     * Define the current operation of the livewire component.
     * The valid options for operation values are: create, view, update.
     *
     * @var string
     */
    protected string $operation = 'update';

    public string $startAt;
    public string $endAt;

    /**
     * Handle the `mount` lifecycle event.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     */
    public function mount(): void
    {
        parent::mount();
        $this->startAt = Carbon::parse($this->webinar->start_at)->format('Y-m-d\TH:i');
        $this->endAt   = Carbon::parse($this->webinar->end_at)->format('Y-m-d\TH:i');
    }

    /**
     * The validation rules for promo model.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['webinarThumbnail'] = 'nullable';
        return $rules;
    }

    /**
     * Save the webinar model.
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     */
    public function save()
    {
        $this->webinar['start_at'] = Carbon::createFromFormat('Y-m-d\TH:i', $this->startAt);
        $this->webinar['end_at'] = Carbon::createFromFormat('Y-m-d\TH:i', $this->endAt);
        parent::save();
    }

    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.webinars.edit_webinar')
            ->extends('cms::_layouts.app')
            ->section('content');
    }
}
