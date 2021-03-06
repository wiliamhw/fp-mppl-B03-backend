<?php

namespace App\Http\Livewire\Cms\Webinars;

use App\Http\Livewire\Concerns\HasMedia;
use App\Models\Category;
use App\Models\Webinar;
use Cms\Livewire\Concerns\ResolveCurrentAdmin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

abstract class WebinarForm extends Component
{
    use AuthorizesRequests;
    use ResolveCurrentAdmin;
    use WithFileUploads;
    use HasMedia;

    /**
     * The related webinar instance.
     *
     * @var Webinar
     */
    public Webinar $webinar;

    /**
     * Store is published value.
     *
     * @var string
     */
    public string $isPublished;

    /**
     * Defines the options.
     *
     * @var string[]
     */
    public array $categoryOptions;

    /**
     * Defines the options of is published value.
     *
     * @var string[]
     */
    public array $isPublishedOptions = [
        'true'  => 'True',
        'false' => 'False',
    ];

    /**
     * Define the current operation of the livewire component.
     * The valid options for operation values are: create, view, update.
     *
     * @var string
     */
    protected string $operation;

    /**
     * Store image thumbnail file.
     *
     * @var mixed
     */
    public $webinarThumbnail;

    /**
     * Store participants value.
     *
     * @var int
     */
    public int $participants;

    /**
     * Store image thumbnail url.
     *
     * @var string
     */
    public string|null $webinarThumbnailUrl;

    /**
     * The validation rules for promo model.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'webinar.category_id'       => 'required|integer|between:0,4294967295|exists:categories,id',
            'webinar.title'             => [
                'required', 'string', 'min:2', 'max:255',
                Rule::unique('webinars', 'title')
                    ->ignore($this->webinar->id, 'id')
            ],
            'webinar.brief_description' => 'required|string|min:2|max:383',
            'webinar.description'       => 'required|string|min:2|max:16777215',
            'webinar.start_at'          => 'required|date',
            'webinar.end_at'            => 'required|date|after_or_equal:webinar.start_at',
            'webinar.max_participants'  => 'required|integer|between:0,4294967295',
            'webinar.price'             => 'nullable|integer|between:0,4294967295',
            'webinar.zoom_id'           => 'nullable|string|min:2|max:11',

            'isPublished'               => 'required|string|in:true,false',
            'webinarThumbnail'          => 'required|image',
        ];
    }


    /**
     * Redirect and go back to index page.
     *
     * @return mixed
     */
    public function backToIndex()
    {
        return redirect()->to(
            route('cms.webinars.index')
        );
    }

    /**
     * Confirm Admin authorization to access the datatable resources.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     */
    protected function confirmAuthorization(): void
    {
        $permission = 'cms.' . $this->webinar->getTable() . '.' . $this->operation;

        if (!$this->getCurrentAdminProperty()->can($permission)) {
            throw new AuthorizationException();
        }
    }

    /**
     * Redirect to the edit webinar page.
     *
     * @return mixed
     */
    public function edit()
    {
        return redirect()->to(
            route('cms.webinars.edit', ['webinar' => $this->webinar])
        );
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
                'title' => 'Webinars',
                'url' => route('cms.webinars.index'),
            ]
        ];
    }

    /**
     * Get the success message after `save` action called successfully.
     *
     * @return string
     */
    protected function getSuccessMessage(): string
    {
        return ($this->operation === 'create') ?
            'The new webinar has been saved.' :
            'The webinar has been updated.';
    }

    /**
     * Handle the `mount` lifecycle event.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     */
    public function mount(): void
    {
        $this->confirmAuthorization();

        $this->participants = $this->webinar->participants ?? 0;

        $this->categoryOptions = Category::pluck('name', 'id')->toArray();
        $this->isPublished = $this->webinar->isPublished() ? 'true' : 'false';

        $this->webinarThumbnail = $this->webinar->getFirstMedia(Webinar::IMAGE_COLLECTION) ?? null;
        $this->webinarThumbnailUrl = $this->webinarThumbnail?->getUrl('large');
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
        if (($this->operation !== 'create') && ($this->operation !== 'update')) {
            return redirect()->to(route('cms.webinars.index'));
        }

        $this->confirmAuthorization();
        $this->validate();

        $this->webinar->generatePublishedAt($this->isPublished);
        $this->webinar->generateType();

        $this->webinar->storeMedia(
            $this->webinarThumbnail, Webinar::IMAGE_COLLECTION, $this->webinarThumbnailUrl ?? null
        );
        $this->clearMedia($this->webinarThumbnail);

        $this->webinar->save();

        session()->flash('alertType', 'success');
        session()->flash('alertMessage', $this->getSuccessMessage());

        return redirect()->to(route('cms.webinars.index'));
    }
}
