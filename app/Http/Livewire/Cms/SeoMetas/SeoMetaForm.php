<?php

namespace App\Http\Livewire\Cms\SeoMetas;

use App\Models\SeoMeta;
use Cms\Livewire\Concerns\ResolveCurrentAdmin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

abstract class SeoMetaForm extends Component
{
    use AuthorizesRequests;
    use ResolveCurrentAdmin;

    /**
     * Register the media component names.
     *
     * @var string[]
     */
    public array $mediaComponentNames = [
        'seoImage',
    ];

    /**
     * Defines the open graph type options.
     *
     * @var string[]
     */
    public array $openGraphTypes = [
        'article' => 'article',
        'website' => 'website',
    ];

    /**
     * Define the current operation of the livewire component.
     * The valid options for operation values are: create, view, update.
     *
     * @var string
     */
    protected string $operation;

    /**
     * The validation rules for seo meta model.
     *
     * @var string[]
     */
    protected array $rules = [
        'seoMeta.seo_url'         => 'required|string|min:2|max:255',
        'seoMeta.seo_title'       => 'required|string|min:2|max:60',
        'seoMeta.seo_description' => 'required|string|min:2|max:150',
        'seoMeta.open_graph_type' => 'required|in:article,website',
    ];

    /**
     * The Media Library Pro's Request instance.
     *
     * @var mixed
     */
    public $seoImage;

    /**
     * The related seo meta instance.
     *
     * @var SeoMeta
     */
    public SeoMeta $seoMeta;

    /**
     * Redirect and go back to index page.
     *
     * @return mixed
     */
    public function backToIndex()
    {
        return redirect()->to(
            route('cms.seo_metas.index')
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
        $permission = 'cms.'.$this->seoMeta->getTable().'.'.$this->operation;

        if (!$this->getCurrentAdminProperty()->can($permission)) {
            throw new AuthorizationException();
        }
    }

    /**
     * Redirect to the edit seo meta page.
     *
     * @return mixed
     */
    public function edit()
    {
        return redirect()->to(
            route('cms.seo_metas.edit', ['seoMeta' => $this->seoMeta])
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
                'title' => 'Seo Metas',
                'url'   => route('cms.seo_metas.index'),
            ],
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
            'The new seo meta has been saved.' :
            'The seo meta has been updated.';
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

        if ($this->seoMeta->exists && ($this->seoMeta->getAttribute('model') !== null)) {
            throw new AuthorizationException();
        }
    }

    /**
     * Save the seo meta model.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function save()
    {
        if (($this->operation !== 'create') && ($this->operation !== 'update')) {
            return redirect()->to(route('cms.seo_metas.index'));
        }

        $this->confirmAuthorization();
        $this->validate();

        $this->seoMeta->save();
        $this->seoMeta->addFromMediaLibraryRequest($this->seoImage)
            ->toMediaCollection('seo_image');

        $this->clearMedia();

        session()->flash('alertType', 'success');
        session()->flash('alertMessage', $this->getSuccessMessage());

        return redirect()->to(route('cms.seo_metas.index'));
    }
}
