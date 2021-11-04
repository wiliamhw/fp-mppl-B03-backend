<?php

namespace Cms\Livewire;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use TypeError;

class DatatableColumn
{
    /**
     * Datatable column's text alignment.
     *
     * @var string
     */
    protected string $align = 'left';

    /**
     * Custom render function.
     *
     * @var Closure|null
     */
    protected ?Closure $customRender = null;

    /**
     * Determine if the datatable column is invisible.
     *
     * @var bool
     */
    protected bool $invisible = false;

    /**
     * Datatable column name.
     *
     * @var string
     */
    protected string $name;

    /**
     * Defines if the datatable column is being sortable.
     *
     * @var bool
     */
    protected bool $sortable = true;

    /**
     * Datatable column's title.
     * This value will be displayed in the datatable's user interface.
     *
     * @var string
     */
    protected string $title;

    /**
     * Datatable column's fixed width.
     *
     * @var string
     */
    protected string $width = '';

    /**
     * DatatableColumn constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Define and override the default text alignment value.
     *
     * @param string $alignment
     *
     * @return $this
     */
    public function align(string $alignment): self
    {
        $this->align = $alignment;

        return $this;
    }

    /**
     * Generate the title based on the column's name.
     *
     * @return string
     */
    protected function generateTitle(): string
    {
        $names = collect(explode('.', $this->name));
        $column = (string) $names->pop();
        $relation = $names->pop();

        $column = Str::title(str_replace('_', ' ', $column));

        if (is_string($relation) && ($relation !== '')) {
            $relation = Str::title(str_replace('_', ' ', Str::singular($relation))).'\'s ';
        }

        return $relation.$column;
    }

    /**
     * Provides the value of column's title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        if (!isset($this->title)) {
            $this->title = $this->generateTitle();
        }

        return $this->title;
    }

    /**
     * Get the invisibility status of the current column.
     *
     * @return bool
     */
    public function isInvisible(): bool
    {
        return $this->invisible;
    }

    /**
     * Make a new datatable column instance using static method.
     *
     * @param string $name
     *
     * @return DatatableColumn
     */
    public static function make(string $name): self
    {
        return new self($name);
    }

    /**
     * Defines if the datatable column can not be sorted.
     *
     * @return $this
     */
    public function notSortable(): self
    {
        $this->sortable = false;

        return $this;
    }

    /**
     * Render the datatable cell's content.
     *
     * @param mixed $model
     *
     * @return string
     */
    public function renderCell($model): string
    {
        if (!$model instanceof Model) {
            throw new TypeError('Datatable render() method should receive an instance of eloquent model.');
        }

        if ($this->invisible) {
            return '';
        }

        $closure = $this->customRender;
        $class = 'text-'.$this->align;
        $content = ($closure instanceof Closure) ?
            $closure($model) :
            data_get($model, $this->name);

        $width = ($this->width !== '') ? ' style="width: '.$this->width.';"' : '';

        return sprintf('<td class="%s"%s>%s</td>', $class, $width, $content);
    }

    /**
     * Render the datatable column header.
     *
     * @param string $sortColumn
     * @param string $sortDirection
     *
     * @return string
     */
    public function renderHeader(string $sortColumn, string $sortDirection): string
    {
        if ($this->invisible) {
            return '';
        }

        $styles = ['text-'.$this->align];

        if ($this->sortable) {
            $styles[] = 'sortable';
        }

        if ($sortColumn === $this->name) {
            $styles[] = 'sort_'.$sortDirection;
        }

        $class = ' class="'.implode(' ', $styles).'"';
        $width = ($this->width !== '') ? ' style="width: '.$this->width.';"' : '';
        $wireAction = $this->sortable ? sprintf('wire:click.prevent="sortBy(\'%s\')"', $this->name) : '';

        return sprintf('<th %s%s%s>%s</th>', $wireAction, $class, $width, $this->getTitle());
    }

    /**
     * Render column visibility option.
     *
     * @param int $index
     *
     * @return string
     */
    public function renderVisibilityOption(int $index): string
    {
        $class = $this->invisible ? 'fa-times-circle' : 'fa-check-circle';

        return sprintf(
            '<div wire:click="toggleVisibility(%d)" class="dt-column-visibility-options col-md-6 col-sm-12 pb-2 pt-2"><i class="icon-nm fa %s"></i> %s</div>',
            $index,
            $class,
            $this->getTitle()
        );
    }

    /**
     * Apply custom render with anonymous function.
     *
     * @param Closure $callback
     *
     * @return $this
     */
    public function renderWith(Closure $callback): self
    {
        $this->customRender = $callback;

        return $this;
    }

    /**
     * Set the invisible property's value for the current column.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setInvisible(bool $value): self
    {
        $this->invisible = $value;

        return $this;
    }

    /**
     * Set the column's title with the given string.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the column's fixed width.
     *
     * @param string $width
     *
     * @return $this
     */
    public function setWidth(string $width): self
    {
        $this->width = $width;

        return $this;
    }
}
