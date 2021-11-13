<?php

namespace App\Http\Livewire\Cms\Categories;

use App\Models\Category;
use Cms\Livewire\Concerns\ResolveCurrentAdmin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

abstract class CategoryForm extends Component
{
    use AuthorizesRequests;
    use ResolveCurrentAdmin;

    /**
     * The related category instance.
     *
     * @var Category
     */
    public Category $category;

    /**
     * Define the current operation of the livewire component.
     * The valid options for operation values are: create, view, update.
     *
     * @var string
     */
    protected string $operation;

    /**
     * The validation rules for category model.
     *
     * @var string[]
     */
    protected array $rules = [
        'category.name' => 'required|string|min:2|max:45',
    ];

    /**
     * Redirect and go back to index page.
     *
     * @return mixed
     */
    public function backToIndex()
    {
        return redirect()->to(
            route('cms.categories.index')
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
        $permission = 'cms.' . $this->category->getTable() . '.' . $this->operation;

        if (!$this->getCurrentAdminProperty()->can($permission)) {
            throw new AuthorizationException();
        }
    }

    /**
     * Redirect to the edit category page.
     *
     * @return mixed
     */
    public function edit()
    {
        return redirect()->to(
            route('cms.categories.edit', ['category' => $this->category])
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
                'title' => 'Categories',
                'url' => route('cms.categories.index'),
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
            'The new category has been saved.' :
            'The category has been updated.';
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
    }

    /**
     * Save the category model.
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     */
    public function save()
    {
        if (($this->operation !== 'create') && ($this->operation !== 'update')) {
            return redirect()->to(route('cms.categories.index'));
        }

        $this->confirmAuthorization();
        $this->validate();

        $this->category->save();

        session()->flash('alertType', 'success');
        session()->flash('alertMessage', $this->getSuccessMessage());

        return redirect()->to(route('cms.categories.index'));
    }
}
