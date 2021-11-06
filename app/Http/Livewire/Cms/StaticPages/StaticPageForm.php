<?php

namespace App\Http\Livewire\Cms\StaticPages;

use App\Models\StaticPage;
use Cms\Livewire\Concerns\ResolveCurrentAdmin;
use Cms\Livewire\Concerns\WithSeoMeta;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

abstract class StaticPageForm extends Component
{
    use AuthorizesRequests;
    use ResolveCurrentAdmin;
    use WithSeoMeta;

    /**
     * Define the main model name which implements
     * the SeoAttachedModel interface.
     *
     * @var string
     */
    protected string $mainModelName = 'staticPage';

    /**
     * Register the media component names.
     *
     * @var string[]
     */
    public array $mediaComponentNames = [];

    /**
     * Defines the options of layout value.
     *
     * @var string[]
     */
    public array $layoutOptions = [
        'default' => 'default',
    ];

    /**
     * Define the current operation of the livewire component.
     * The valid options for operation values are: create, view, update.
     *
     * @var string
     */
    protected string $operation;

    /**
     * Defines the options of published value.
     *
     * @var string[]
     */
    public array $publishedOptions = [
        'No'  => 'No',
        'Yes' => 'Yes',
    ];

    /**
     * The validation rules for static page model.
     *
     * @var string[]
     */
    protected array $rules = [
        'staticPage.name'           => 'required|string|min:2|max:255',
        'staticPage.content'        => 'required|string|min:2|max:16777215',
        'staticPage.youtube_video'  => 'nullable|string|min:2|max:255',
        'staticPage.layout'         => 'required|string|min:2|max:255',
        'staticPage.published'      => 'required|string|min:2|max:255',

        'seoMeta.seo_title.*'       => 'required|string|min:2|max:60',
        'seoMeta.seo_description.*' => 'required|string|min:2|max:150',
        'seoMeta.open_graph_type.*' => 'required|in:article,website',
    ];

    /**
     * The related static page instance.
     *
     * @var StaticPage
     */
    public StaticPage $staticPage;

    /**
     * Redirect and go back to index page.
     *
     * @return mixed
     */
    public function backToIndex()
    {
        return redirect()->to(
            route('cms.static_pages.index')
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
        $permission = 'cms.'.$this->staticPage->getTable().'.'.$this->operation;

        if (!$this->getCurrentAdminProperty()->can($permission)) {
            throw new AuthorizationException();
        }
    }

    /**
     * Redirect to the edit static page page.
     *
     * @return mixed
     */
    public function edit()
    {
        return redirect()->to(
            route('cms.static_pages.edit', ['staticPage' => $this->staticPage])
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
                'title' => 'Static Pages',
                'url'   => route('cms.static_pages.index'),
            ],
        ];
    }

    /**
     * Get computed SEO content meta data.
     *
     * @param StaticPage $staticPage
     * @param string     $locale
     *
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function getSeoContent(StaticPage $staticPage, string $locale): string
    {
        return strip_tags((string) $staticPage->getAttribute('content'));
    }

    /**
     * Get computed SEO url meta data.
     *
     * @param StaticPage $staticPage
     * @param string     $locale
     *
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function getSeoUrl(StaticPage $staticPage, string $locale): string
    {
        return '/'.$staticPage->getAttribute('slug');
    }

    /**
     * Get the success message after `save` action called successfully.
     *
     * @return string
     */
    protected function getSuccessMessage(): string
    {
        return ($this->operation === 'create') ?
            'The new static page has been saved.' :
            'The static page has been updated.';
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

        if ($this->staticPage->getAttribute('layout') === null) {
            $this->staticPage->setAttribute('layout', 'default');
        }

        if ($this->staticPage->getAttribute('published') === null) {
            $this->staticPage->setAttribute('published', 'No');
        }

        if ($this->operation === 'view') {
            $this->rules['staticPage.slug'] = 'required';
        }
    }

    /**
     * Save the static page model.
     *
     * @throws \ErrorException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Livewire\Exceptions\PropertyNotFoundException
     *
     * @return mixed
     */
    public function save()
    {
        if (($this->operation !== 'create') && ($this->operation !== 'update')) {
            return redirect()->to(route('cms.static_pages.index'));
        }

        $this->confirmAuthorization();
        $this->validate();

        $this->staticPage->save();
        $this->saveSeoMeta();

        session()->flash('alertType', 'success');
        session()->flash('alertMessage', $this->getSuccessMessage());

        return redirect()->to(route('cms.static_pages.index'));
    }
}
