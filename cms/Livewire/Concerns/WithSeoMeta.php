<?php

namespace Cms\Livewire\Concerns;

use App\Models\SeoMeta;
use Cms\Contracts\SeoAttachedModel;
use Cms\Exceptions\InvalidPropertyTypeException;
use ErrorException;
use Illuminate\Database\Eloquent\Model;
use Livewire\Exceptions\PropertyNotFoundException;
use RichanFongdasen\I18n\I18nService;

trait WithSeoMeta
{
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
     * The array which would store the uploaded SEO Media
     * for English version of SEO Meta.
     *
     * @var array|null
     */
    public ?array $seoMediaEn = null;

    /**
     * The array which would store the uploaded SEO Media
     * for Indonesian version of SEO Meta.
     *
     * @var array|null
     */
    public ?array $seoMediaId = null;

    /**
     * The array which would store the translated SEO Meta data.
     *
     * @var array
     */
    public array $seoMeta = [];

    /**
     * Defines the blade template which contains the SEO Meta
     * attachable form.
     *
     * @var string
     */
    public string $seoMetaBlade = 'cms::_partials.attached_seo_meta';

    /**
     * Extract the SEO Meta attributes from the given model instance.
     *
     * @param SeoMeta $seoMeta
     * @param array   $result
     *
     * @return array
     */
    protected function extractSeoMetaAttributes(SeoMeta $seoMeta, array $result = []): array
    {
        $ignores = [
            'id',
            'attachable_type',
            'attachable_id',
            'locale',
            'seo_url',
        ];

        $values = $seoMeta->toArray();
        foreach ($values as $key => $value) {
            if (!in_array($key, $ignores, true)) {
                if (!isset($result[$key])) {
                    $result[$key] = [];
                }
                $result[$key][$seoMeta->getAttribute('locale')] = $value;
            }
        }

        return $result;
    }

    /**
     * Get all of the available SEO Meta attributes.
     *
     * @throws InvalidPropertyTypeException
     * @throws PropertyNotFoundException
     * @throws \RichanFongdasen\I18n\Exceptions\InvalidFallbackLanguageException
     *
     * @return array
     */
    public function getAllSeoMetaAttributes(): array
    {
        if (!$this->getAttachedModel()->existsInDatabase()) {
            return $this->getInitialSeoMetaData();
        }

        $result = [];
        $locales = app(I18nService::class)->getLocale();
        $localeKey = config('i18n.language_key', 'language');

        foreach ($locales as $locale) {
            $seoMeta = $this->getAttachedModel()->getSeoMetaAttribute($locale->{$localeKey});
            $result = $this->extractSeoMetaAttributes($seoMeta, $result);
        }

        return !empty($result) ? $result : $this->getInitialSeoMetaData();
    }

    /**
     * Retrieve computed SEO Meta data, if it's defined by the Livewire component.
     *
     * @param string      $callback
     * @param string      $locale
     * @param string|null $default
     *
     * @throws InvalidPropertyTypeException
     * @throws PropertyNotFoundException
     *
     * @return string|null
     */
    protected function getComputedSeoData(string $callback, string $locale, string $default = null): ?string
    {
        return method_exists($this, $callback) ? (string) $this->$callback($this->getAttachedModel(), $locale) : $default;
    }

    /**
     * Get the SEO Attached Model in the current Livewire component.
     *
     * @throws InvalidPropertyTypeException
     * @throws PropertyNotFoundException
     *
     * @return SeoAttachedModel
     */
    public function getAttachedModel(): SeoAttachedModel
    {
        if (!property_exists($this, 'mainModelName')) {
            throw new PropertyNotFoundException('mainModelName', get_class($this));
        }
        $model = $this->{$this->mainModelName};

        if (!($model instanceof SeoAttachedModel) || !($model instanceof Model)) {
            throw new InvalidPropertyTypeException(get_class($this), $this->mainModelName, [SeoAttachedModel::class, Model::class]);
        }

        return $model;
    }

    /**
     * Get the default SEO Form attributes.
     *
     * @return array
     */
    public function getSeoFormAttribute(): array
    {
        return ($this->getSeoFormOperation() === 'view') ? ['disabled' => 'disabled'] : [];
    }

    /**
     * Get the current SEO Form operation.
     *
     * @return string
     */
    public function getSeoFormOperation(): string
    {
        return $this->operation ?? 'view';
    }

    /**
     * Generate the initial SEO Meta data.
     *
     * @return array
     */
    protected function getInitialSeoMetaData(): array
    {
        $locales = app(I18nService::class)->getLocale();
        $localeKey = config('i18n.language_key', 'language');
        $result = ['open_graph_type' => []];

        foreach ($locales as $locale) {
            $result['open_graph_type'][$locale->{$localeKey}] = 'article';
        }

        return $result;
    }

    /**
     * Boot the SEO Meta management for the current Livewire component.
     *
     * @throws InvalidPropertyTypeException
     * @throws PropertyNotFoundException
     * @throws \RichanFongdasen\I18n\Exceptions\InvalidFallbackLanguageException
     */
    public function mountWithSeoMeta(): void
    {
        if (!method_exists($this, 'mountWithMedia')) {
            throw new ErrorException(sprintf(
                'Class %s should use the %s trait.',
                get_class($this),
                WithMedia::class
            ));
        }

        $locales = app(I18nService::class)->getLocale();
        $localeKey = config('i18n.language_key', 'language');
        $this->seoMeta = $this->getAllSeoMetaAttributes();

        foreach ($locales as $locale) {
            $seoMediaProperty = 'seoMedia'.ucfirst($locale->{$localeKey});

            $this->{$seoMediaProperty} = null;

            if (property_exists($this, 'mediaComponentNames') && is_array($this->mediaComponentNames)) {
                $this->mediaComponentNames[] = $seoMediaProperty;
            }
        }
    }

    /**
     * Save the SEO Meta data to the database and process the uploaded media as well.
     *
     * @throws InvalidPropertyTypeException
     * @throws PropertyNotFoundException
     * @throws \RichanFongdasen\I18n\Exceptions\InvalidFallbackLanguageException
     */
    public function saveSeoMeta(): void
    {
        $locales = app(I18nService::class)->getLocale();
        $localeKey = config('i18n.language_key', 'language');

        foreach ($locales as $locale) {
            $key = $locale->{$localeKey};
            $seoMeta = $this->getAttachedModel()->getSeoMetaAttribute($key);
            $seoMediaProperty = 'seoMedia'.ucfirst($key);

            $seoMeta->fill([
                'seo_url'         => $this->getComputedSeoData('getSeoUrl', $key),
                'seo_title'       => data_get($this->seoMeta, 'seo_title.'.$key),
                'seo_description' => data_get($this->seoMeta, 'seo_description.'.$key),
                'open_graph_type' => data_get($this->seoMeta, 'open_graph_type.'.$key),
                'seo_content'     => $this->getComputedSeoData('getSeoContent', $key),
            ])->save();

            $seoMeta->addFromMediaLibraryRequest($this->{$seoMediaProperty})
                ->toMediaCollection('seo_image');
        }
    }
}
