<?php

namespace Cms\Models\Concerns;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\App;
use RichanFongdasen\I18n\I18nService;
use RichanFongdasen\I18n\Locale;

trait HasSeoMeta
{
    /**
     * Determine if the current model exists in database.
     *
     * @return bool
     */
    public function existsInDatabase(): bool
    {
        return (bool) $this->exists;
    }

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    abstract public function getKey();

    /**
     * Set the SEO Title attribute accessor.
     *
     * @throws \RichanFongdasen\I18n\Exceptions\InvalidFallbackLanguageException
     *
     * @return string
     */
    public function getSeoTitleAttribute(): string
    {
        return (string) $this->getSeoMetaAttribute()->getAttribute('seo_title');
    }

    /**
     * Set the SEO Description attribute accessor.
     *
     * @throws \RichanFongdasen\I18n\Exceptions\InvalidFallbackLanguageException
     *
     * @return string
     */
    public function getSeoDescriptionAttribute(): string
    {
        return (string) $this->getSeoMetaAttribute()->getAttribute('seo_description');
    }

    /**
     * Set the SEO URL attribute accessor.
     *
     * @throws \RichanFongdasen\I18n\Exceptions\InvalidFallbackLanguageException
     *
     * @return string
     */
    public function getSeoUrlAttribute(): string
    {
        return (string) $this->getSeoMetaAttribute()->getAttribute('seo_url');
    }

    /**
     * Set the SEO Image attribute accessor.
     *
     * @throws \RichanFongdasen\I18n\Exceptions\InvalidFallbackLanguageException
     *
     * @return string
     */
    public function getSeoImageAttribute(): string
    {
        $media = $this->getSeoMetaAttribute()->getFirstMediaUrl('seo_image', 'seo_image_large');

        return ($media === '') ? config('seo.image') : asset($media);
    }

    /**
     * Get the SEO Meta instance matched with the current application locale.
     *
     * @param string|null $locale
     *
     * @throws \RichanFongdasen\I18n\Exceptions\InvalidFallbackLanguageException
     *
     * @return SeoMeta
     */
    public function getSeoMetaAttribute(string $locale = null): SeoMeta
    {
        $locale = $locale ?? $this->getSeoMetaLocale();
        $seoMeta = $this->seoMetas->where('locale', $locale)->first();

        if (!($seoMeta instanceof SeoMeta)) {
            $seoMeta = new SeoMeta();
            $seoMeta->fill([
                'attachable_type' => get_class($this->newInstance()),
                'attachable_id'   => $this->getKey(),
                'locale'          => $locale,
            ]);
        }

        return $seoMeta;
    }

    /**
     * Get join table attributes.
     *
     * @return string[]
     */
    protected function getSeoMetaJoinAttributes(): array
    {
        return [
            $this->getTable().'.*',
            'seo_metas.seo_url',
            'seo_metas.seo_title',
            'seo_metas.seo_description',
            'seo_metas.seo_content',
            'seo_metas.open_graph_type',
        ];
    }

    /**
     * Get the Locale string for the current application request.
     *
     * @throws \RichanFongdasen\I18n\Exceptions\InvalidFallbackLanguageException
     *
     * @return string
     */
    protected function getSeoMetaLocale(): string
    {
        $localeKey = config('i18n.language_key', 'language');

        $key = App::getLocale();
        $locale = app(I18nService::class)->getLocale($key);
        if (!($locale instanceof Locale)) {
            $locale = app(I18nService::class)->defaultLocale();
        }

        return (string) data_get($locale, $localeKey, $key);
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    abstract public function getTable();

    /**
     * Define a polymorphic one-to-many relationship.
     *
     * @param string      $related
     * @param string      $name
     * @param string|null $type
     * @param string|null $id
     * @param string|null $localKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    abstract public function morphMany($related, $name, $type = null, $id = null, $localKey = null);

    /**
     * Create a new instance of the given model.
     *
     * @param array $attributes
     * @param bool  $exists
     *
     * @return static
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    abstract public function newInstance($attributes = [], $exists = false);

    /**
     * Add an additional scope to join the seo_metas table
     * and make the SEO Meta content more easier to search.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @throws \RichanFongdasen\I18n\Exceptions\InvalidFallbackLanguageException
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeJoinSeoMeta(Builder $query): Builder
    {
        return $query->leftJoin('seo_metas', function (JoinClause $join) {
            $join->on('seo_metas.attachable_id', '=', $this->getTable().'.'.$this->getKeyName());
        })->select($this->getSeoMetaJoinAttributes())
            ->where('seo_metas.attachable_type', get_class($this))
            ->where('seo_metas.locale', $this->getSeoMetaLocale())
            ->whereNull('seo_metas.deleted_at');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function seoMetas(): MorphMany
    {
        return $this->morphMany(SeoMeta::class, 'attachable')->with(['media']);
    }
}
