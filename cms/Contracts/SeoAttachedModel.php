<?php

namespace Cms\Contracts;

use App\Models\SeoMeta;

interface SeoAttachedModel
{
    /**
     * Determine if the current model exists in database.
     *
     * @return bool
     */
    public function existsInDatabase(): bool;

    /**
     * Get an attribute from the model.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute($key);

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey();

    /**
     * Get the SEO Meta instance matched with the current application locale.
     *
     * @param string|null $locale
     *
     * @throws \RichanFongdasen\I18n\Exceptions\InvalidFallbackLanguageException
     *
     * @return SeoMeta
     */
    public function getSeoMetaAttribute(string $locale = null): SeoMeta;
}
