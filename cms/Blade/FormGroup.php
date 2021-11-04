<?php

namespace Cms\Blade;

use Closure;
use Collective\Html\FormBuilder;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;

class FormGroup implements Htmlable
{
    /**
     * Default input class name.
     *
     * @var string
     */
    protected string $defaultInputClass;

    /**
     * The currently used error bag.
     *
     * @var ViewErrorBag|null
     */
    protected ?ViewErrorBag $errorBag;

    /**
     * Form group's error names.
     *
     * @var array
     */
    protected array $errorNames = [];

    /**
     * Form group's guideline string.
     *
     * @var string
     */
    protected string $guideline = '';

    /**
     * Input creator closure.
     *
     * @var Closure
     */
    protected Closure $inputCreator;

    /**
     * Form group/input name.
     *
     * @var string
     */
    protected string $name;

    /**
     * Form group/input options.
     *
     * @var Collection
     */
    protected Collection $options;

    /**
     * Form group HTML templates.
     *
     * Form group consists of :
     *   - Form label
     *   - Form input
     *   - Form error message
     *   - Thumbnail (optional)
     *   - Form guideline
     *
     * @var array
     */
    protected static array $templates = [
        'error'      => '<div>%s</div>',
        'errorGroup' => '<div class="invalid-feedback">%s</div>',
        'formGroup'  => '<div class="form-group">%s%s%s%s%s</div>',
        'guideline'  => '<p>%s</p>',
        'input'      => '%s',
        'label'      => '%s',
        'thumbnail'  => '<div><img src="%s" alt="%s" class="form-group-thumbnail" /></div>',
    ];

    /**
     * Form group/input thumbnail url.
     *
     * @var string
     */
    protected string $thumbnail;

    /**
     * Form group label title.
     *
     * @var string
     */
    protected string $title;

    /**
     * FormGroup constructor.
     *
     * @param string            $name
     * @param array             $options
     * @param ViewErrorBag|null $errorBag
     */
    public function __construct(string $name, array $options = [], ViewErrorBag $errorBag = null)
    {
        $this->name = $name;
        $this->options = $this->processOptions($options);
        $this->errorBag = $errorBag;
    }

    /**
     * Set the default input class name.
     *
     * @param string $class
     *
     * @return FormGroup
     */
    public function setDefaultInputClass(string $class = ''): self
    {
        $this->defaultInputClass = !empty($class) ? $class : 'form-control';

        return $this;
    }

    /**
     * Set error names for current form group.
     *
     * @param array $errorNames
     *
     * @return FormGroup
     */
    public function setErrorNames(array $errorNames = []): self
    {
        $this->errorNames = !empty($errorNames) ?
            $errorNames :
            [$this->name];

        return $this;
    }

    /**
     * Set guideline string for current form group.
     *
     * @param string $guideline
     *
     * @return FormGroup
     */
    public function setGuideline(string $guideline = ''): self
    {
        $this->guideline = $guideline;

        return $this;
    }

    /**
     * Set input creator closure for current form group.
     *
     * @param Closure $inputCreator
     *
     * @return FormGroup
     */
    public function setInputCreator(Closure $inputCreator): self
    {
        $this->inputCreator = $inputCreator;

        return $this;
    }

    /**
     * Set the thumbnail for current form group.
     *
     * @param string|null $thumbnail
     *
     * @return FormGroup
     */
    public function setThumbnail(?string $thumbnail): self
    {
        if ($thumbnail !== null) {
            $this->thumbnail = $thumbnail;
        }

        return $this;
    }

    /**
     * Set the title for current form group.
     *
     * @param string $title
     *
     * @return FormGroup
     */
    public function setTitle(string $title = ''): self
    {
        $this->title = !empty($title) ? $title : $this->resolveTitle($this->name);

        return $this;
    }

    /**
     * Render form group's HTML string.
     *
     * @return string
     */
    public function toHtml(): string
    {
        $this->options->put('wire:model.defer', $this->name);
        $this->options->put('class', $this->defaultInputClass);

        $creator = $this->inputCreator;
        $error = $this->renderErrors($this->getErrorMessages($this->errorNames));

        if (!empty($error)) {
            $this->options->put('class', $this->defaultInputClass.' is-invalid');
        }

        return sprintf(
            self::$templates['formGroup'],
            $this->renderLabel(),
            $creator($this->builder(), $this->name, $this->options),
            $error,
            $this->renderGuideline(),
            $this->renderThumbnail()
        );
    }

    /**
     * Get the HTML string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toHtml();
    }

    /**
     * Retrieve LaravelCollective FormBuilder object.
     *
     * @return FormBuilder
     */
    protected function builder(): FormBuilder
    {
        return app('form');
    }

    /**
     * Get error message based on the given error names.
     *
     * @param array $errorNames
     *
     * @return array
     */
    protected function getErrorMessages(array $errorNames): array
    {
        if (($this->errorBag === null) || ($this->errorBag->count() === 0)) {
            return [];
        }

        foreach ($errorNames as $name) {
            $error = (array) $this->errorBag->get($name);

            if (!empty($error)) {
                return $error;
            }
        }

        return [];
    }

    /**
     * Process form group/input options.
     *
     * @param array $options
     *
     * @return Collection
     */
    protected function processOptions(array $options): Collection
    {
        $default = [
            'class'    => 'form-control',
            'required' => true,
        ];
        $result = collect(array_unique(array_merge($default, $options)));

        $this->setDefaultInputClass((string) $result->get('class'));
        $this->setErrorNames((array) $result->get('errorNames'));
        $this->setGuideline((string) $result->get('guideline'));
        $this->setThumbnail((string) $result->get('thumbnail'));
        $this->setTitle((string) $result->get('title'));

        $result->forget('errorNames');
        $result->forget('guideline');
        $result->forget('thumbnail');
        $result->forget('title');

        return $result;
    }

    /**
     * Render error messages.
     *
     * @param array $errors
     *
     * @return string
     */
    protected function renderErrors(array $errors): string
    {
        $result = '';

        if (empty($errors)) {
            return $result;
        }

        foreach ($errors as $error) {
            $result .= sprintf(self::$templates['error'], $error);
        }

        return sprintf(self::$templates['errorGroup'], $result);
    }

    /**
     * Render guideline element for current form group/input.
     *
     * @return string
     */
    protected function renderGuideline(): string
    {
        return empty($this->guideline) ?
            '' :
            sprintf(self::$templates['guideline'], $this->guideline);
    }

    /**
     * Render label element for current form group/input.
     *
     * @return string
     */
    protected function renderLabel(): string
    {
        return sprintf(
            self::$templates['label'],
            $this->builder()->label($this->name, $this->title)
        );
    }

    /**
     * Render thumbnail element for current form group/input.
     *
     * @return string
     */
    protected function renderThumbnail(): string
    {
        return empty($this->thumbnail) ?
            '' :
            sprintf(self::$templates['thumbnail'], $this->thumbnail, $this->title);
    }

    /**
     * Resolve default session error names for current form group/input.
     *
     * @param string $inputName
     *
     * @return array
     */
    protected function resolveErrorNames(string $inputName): array
    {
        $inputName = str_replace(['[]', '[', ']'], ['.*', '.', ''], $inputName);

        $result = collect([$inputName]);
        $path = collect(explode('.', $inputName));

        for ($i = 1; $i < $path->count(); $i++) {
            $path->put($i, '*');

            $result->push($path->implode('.'));
        }

        return $result->unique()->values()->all();
    }

    /**
     * Resolve default title/label for current form group/input.
     *
     * @param string $inputName
     *
     * @return string
     */
    protected function resolveTitle(string $inputName): string
    {
        $inputName = str_replace(['[]', '[', ']'], ['.*', '.', ''], $inputName);
        $path = collect(explode('.', $inputName));

        if (count($path) >= 2) {
            $path->shift();
        }

        $method = ($path->count() <= 2) ? 'first' : 'last';
        $title = (string) $path->$method(function ($value) {
            return $value !== '*';
        });

        $title = str_replace(['_', '-'], ' ', $title);

        return Str::title($title);
    }

    /**
     * Set the error bag for the current form group.
     *
     * @param ViewErrorBag $errorBag
     */
    public function setErrorBag(ViewErrorBag $errorBag): void
    {
        $this->errorBag = $errorBag;
    }
}
