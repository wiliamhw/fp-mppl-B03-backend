<?php

namespace Cms\Blade;

use BadMethodCallException;
use Collective\Html\FormBuilder as Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ViewErrorBag;
use RichanFongdasen\I18n\Locale;

/**
 * Class FormBuilder.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class FormBuilder
{
    /**
     * LaravelCollective FormBuilder object.
     *
     * @var Builder
     */
    protected Builder $builder;

    /**
     * Current multilingual group's locale.
     *
     * @var Locale|null
     */
    protected ?Locale $currentLocale = null;

    /**
     * The error bag for the current view instance.
     *
     * @var ViewErrorBag|null
     */
    protected ?ViewErrorBag $errorBag = null;

    /**
     * FormBuilder constructor.
     *
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Create a color input form group.
     *
     * @param string $name
     * @param array  $options
     *
     * @return FormGroup
     */
    public function color(string $name, array $options = []): FormGroup
    {
        return $this->generateInput('color', $name, $options);
    }

    /**
     * Close the last multilingual form group.
     *
     * @return $this
     */
    public function closeMultilingualGroup(): self
    {
        $this->currentLocale = null;

        return $this;
    }

    /**
     * Create new multilingual form group by the given locale.
     *
     * @param Locale $locale
     *
     * @return $this
     */
    public function createMultilingualGroup(Locale $locale): self
    {
        $this->currentLocale = $locale;

        return $this;
    }

    /**
     * Create a date input form group.
     *
     * @param string $name
     * @param array  $options
     *
     * @return FormGroup
     */
    public function date(string $name, array $options = []): FormGroup
    {
        return $this->generateInput('date', $name, $options);
    }

    /**
     * Create a datetime input form group.
     *
     * @param string $name
     * @param array  $options
     *
     * @return FormGroup
     */
    public function datetime(string $name, array $options = []): FormGroup
    {
        return $this->generateInput('datetime', $name, $options);
    }

    /**
     * Create an email input form group.
     *
     * @param string $name
     * @param array  $options
     *
     * @return FormGroup
     */
    public function email(string $name, array $options = []): FormGroup
    {
        return $this->generateInput('email', $name, $options);
    }

    /**
     * Create a file input form group.
     *
     * @param string $name
     * @param array  $options
     *
     * @return FormGroup
     */
    public function file(string $name, array $options = []): FormGroup
    {
        [$name, $options] = $this->fixMultilingualInputAttributes($name, $options);

        return (new FormGroup($name, $options, $this->errorBag))->setInputCreator(
            static function (Builder $builder, string $name, Collection $options) {
                return $builder->file($name, $options->toArray());
            }
        );
    }

    /**
     * Fix the multilingual form input attributes.
     *
     * @param string $name
     * @param array  $options
     *
     * @return array
     */
    protected function fixMultilingualInputAttributes(string $name, array $options = []): array
    {
        if ($this->currentLocale instanceof Locale) {
            $segments = explode('.', $name);
            if (count($segments) === 1) {
                array_unshift($segments, 'translations');
            }

            if (!isset($options['id'])) {
                $options['id'] = $segments[0].'_'.$segments[1].'_'.$this->currentLocale->language;
            }

            $name = sprintf('%s.%s.%s', $segments[0], $segments[1], data_get($this->currentLocale, 'language', App::getLocale()));
        }

        return [$name, $options];
    }

    /**
     * Create a number input form group.
     *
     * @param string $name
     * @param array  $options
     *
     * @return FormGroup
     */
    public function number(string $name, array $options = []): FormGroup
    {
        return $this->generateInput('number', $name, $options);
    }

    /**
     * Create a password input form group.
     *
     * @param string $name
     * @param array  $options
     *
     * @return FormGroup
     */
    public function password(string $name, array $options = []): FormGroup
    {
        [$name, $options] = $this->fixMultilingualInputAttributes($name, $options);

        return (new FormGroup($name, $options, $this->errorBag))->setInputCreator(
            static function (Builder $builder, string $name, Collection $options) {
                return $builder->password($name, $options->toArray());
            }
        );
    }

    /**
     * Create a range input form group.
     *
     * @param string $name
     * @param array  $options
     *
     * @return FormGroup
     */
    public function range(string $name, array $options = []): FormGroup
    {
        return $this->generateInput('range', $name, $options);
    }

    /**
     * Create a select input form group.
     *
     * @param string $name
     * @param array  $list
     * @param array  $selectAttributes
     * @param array  $optionsAttributes
     * @param array  $optgroupsAttributes
     *
     * @return FormGroup
     */
    public function select(
        string $name,
        array $list = [],
        array $selectAttributes = [],
        array $optionsAttributes = [],
        array $optgroupsAttributes = []
    ): FormGroup {
        [$name, $selectAttributes] = $this->fixMultilingualInputAttributes($name, $selectAttributes);

        return (new FormGroup($name, $selectAttributes, $this->errorBag))->setInputCreator(
            static function (Builder $builder, string $name, Collection $options) use ($list, $optionsAttributes, $optgroupsAttributes) {
                return $builder->select(
                    $name,
                    $list,
                    null,
                    $options->toArray(),
                    $optionsAttributes,
                    $optgroupsAttributes
                );
            }
        );
    }

    /**
     * Create a select range input form group.
     *
     * @param string $name
     * @param string $begin
     * @param string $end
     * @param array  $options
     *
     * @return FormGroup
     */
    public function selectRange(
        string $name,
        string $begin,
        string $end,
        array $options = []
    ): FormGroup {
        [$name, $options] = $this->fixMultilingualInputAttributes($name, $options);

        return (new FormGroup($name, $options, $this->errorBag))->setInputCreator(
            static function (Builder $builder, string $name, Collection $options) use ($begin, $end) {
                return $builder->selectRange($name, $begin, $end, null, $options->toArray());
            }
        );
    }

    /**
     * Create a tel input form group.
     *
     * @param string $name
     * @param array  $options
     *
     * @return FormGroup
     */
    public function tel(string $name, array $options = []): FormGroup
    {
        return $this->generateInput('tel', $name, $options);
    }

    /**
     * Create a text input form group.
     *
     * @param string $name
     * @param array  $options
     *
     * @return FormGroup
     */
    public function text(string $name, array $options = []): FormGroup
    {
        return $this->generateInput('text', $name, $options);
    }

    /**
     * Create a textarea input form group.
     *
     * @param string $name
     * @param array  $options
     *
     * @return FormGroup
     */
    public function textarea(string $name, array $options = []): FormGroup
    {
        return $this->generateInput('textarea', $name, $options);
    }

    /**
     * Create a time input form group.
     *
     * @param string $name
     * @param array  $options
     *
     * @return FormGroup
     */
    public function time(string $name, array $options = []): FormGroup
    {
        return $this->generateInput('time', $name, $options);
    }

    /**
     * Create a url input form group.
     *
     * @param string $name
     * @param array  $options
     *
     * @return FormGroup
     */
    public function url(string $name, array $options = []): FormGroup
    {
        return $this->generateInput('url', $name, $options);
    }

    /**
     * Dynamically call methods from laravel collective's form builder.
     *
     * @param string $method
     * @param mixed  $args
     *
     * @throws BadMethodCallException
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        $callable = [$this->builder, $method];

        if (!is_callable($callable)) {
            throw new BadMethodCallException('Call to undefined method: '.$method.'()');
        }

        return call_user_func_array($callable, $args);
    }

    /**
     * Create a dynamic input form group.
     *
     * @param string $type
     * @param string $name
     * @param array  $options
     *
     * @return FormGroup
     */
    protected function generateInput(string $type, string $name, array $options = []): FormGroup
    {
        [$name, $options] = $this->fixMultilingualInputAttributes($name, $options);

        return (new FormGroup($name, $options, $this->errorBag))->setInputCreator(
            function (Builder $builder, string $name, Collection $options) use ($type) {
                return $builder->$type($name, null, $options->toArray());
            }
        );
    }

    /**
     * Set the error bag.
     *
     * @param ViewErrorBag $errorBag
     */
    public function setErrorBag(ViewErrorBag $errorBag): void
    {
        $this->errorBag = $errorBag;
    }
}
