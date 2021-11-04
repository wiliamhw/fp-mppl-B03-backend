<?php

namespace Cms\QueryBuilders\Concerns;

use Illuminate\Database\Eloquent\Builder;
use RichanFongdasen\I18n\I18nService;
use Spatie\QueryBuilder\QueryBuilder;

trait SupportTranslations
{
    /**
     * Get a list of allowed searchable columns which can be used in any search operations.
     *
     * @return string[]
     */
    abstract protected function getAllowedSearch(): array;

    /**
     * Get the query builder instance.
     *
     * @throws \RichanFongdasen\I18n\Exceptions\InvalidFallbackLanguageException
     *
     * @return QueryBuilder|Builder
     */
    public function getBuilder()
    {
        $this->setLocale();

        $search = request('search');

        if ($search !== null) {
            $this->builder->whereLike($this->getAllowedSearch(), $search);
        }

        return $this->builder->joinTranslation()->with(['translations']);
    }

    /**
     * Set locale for the current API request.
     *
     * @throws \RichanFongdasen\I18n\Exceptions\InvalidFallbackLanguageException
     */
    protected function setLocale(): void
    {
        $key = request('locale');
        if ($key === null) {
            return;
        }

        $i18n = app(I18nService::class);

        $locale = $i18n->getLocale($key) ?? $i18n->defaultLocale();
        $localeKey = config('i18n.language_key');

        app()->setLocale($locale->{$localeKey});
    }
}
