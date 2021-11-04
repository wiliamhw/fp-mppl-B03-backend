<?php

namespace Cms\Services;

use App\Models\SeoMeta;

class SeoService
{
    /**
     * Current request's SEO meta values.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $meta;

    /**
     * SEO Meta templates.
     *
     * @var array
     */
    protected static $templates = [
        'charset' => '<meta charset="%s">'.PHP_EOL.'<meta http-equiv="Content-Type" content="text/html; charset=%s">'.PHP_EOL,
        'common'  => '<meta name="%s" content="%s">'.PHP_EOL,
        'social'  => '<meta property="%s" content="%s">'.PHP_EOL,
        'title'   => '<title>%s</title>'.PHP_EOL,
    ];

    /**
     * SeoService constructor.
     */
    public function __construct()
    {
        $this->meta = collect(config('seo'));

        $this->meta->forget('mix_title_with_website_name');
        $this->meta->put('url', request()->fullUrl());
    }

    /**
     * Get the meta value for the given key.
     *
     * @param string $key
     *
     * @return string
     */
    public function getMetaValue(string $key): string
    {
        return (string) data_get($this->meta, $key);
    }

    /**
     * Generate the facebook application id meta tag.
     *
     * @return string
     */
    protected function getFacebookAppIdTag(): string
    {
        $facebookId = $this->meta->get('facebook_app_id');

        return ($facebookId === null || $facebookId === '') ? '' : sprintf(self::$templates['social'], 'fb:app_id', $facebookId);
    }

    /**
     * Generate the open graph meta tags.
     *
     * @return string
     */
    protected function getOpenGraphTags(): string
    {
        return sprintf(
            '%s%s%s%s%s%s',
            $this->getFacebookAppIdTag(),
            sprintf(self::$templates['social'], 'og:type', $this->meta->get('open_graph_type')),
            sprintf(self::$templates['social'], 'og:url', $this->meta->get('url')),
            sprintf(self::$templates['social'], 'og:title', $this->getTitleValue()),
            sprintf(self::$templates['social'], 'og:description', $this->meta->get('description')),
            sprintf(self::$templates['social'], 'og:image', $this->meta->get('image'))
        );
    }

    /**
     * Generate title meta content.
     *
     * @return string
     */
    protected function getTitleValue(): string
    {
        return (bool) config('seo.mix_title_with_website_name') ?
            $this->meta->get('title').' - '.$this->meta->get('website_name') :
            $this->meta->get('title');
    }

    /**
     * Generate the twitter card meta tags.
     *
     * @return string
     */
    protected function getTwitterCardTags(): string
    {
        return sprintf(
            '%s%s%s%s%s%s',
            $this->getTwitterCreatorTags(),
            sprintf(self::$templates['social'], 'twitter:card', $this->meta->get('twitter_card_type')),
            sprintf(self::$templates['social'], 'twitter:url', $this->meta->get('url')),
            sprintf(self::$templates['social'], 'twitter:title', $this->getTitleValue()),
            sprintf(self::$templates['social'], 'twitter:description', $this->meta->get('description')),
            sprintf(self::$templates['social'], 'twitter:image', $this->meta->get('image'))
        );
    }

    /**
     * Generate the twitter card creator meta tags.
     *
     * @return string
     */
    protected function getTwitterCreatorTags(): string
    {
        $twitterAccount = $this->meta->get('twitter_account');

        if ($twitterAccount === null || $twitterAccount === '') {
            return '';
        }

        return sprintf(
            '%s%s',
            sprintf(self::$templates['social'], 'twitter:site', $this->meta->get('twitter_account')),
            sprintf(self::$templates['social'], 'twitter:creator', $this->meta->get('twitter_account'))
        );
    }

    /**
     * Load the SEO meta information from the database.
     */
    protected function loadMetaFromDatabase(): void
    {
        $meta = SeoMeta::where('seo_url', request()->path())->first();

        if ($meta instanceof SeoMeta) {
            $this->setMetaValue('title', $meta->seo_title)
                ->setMetaValue('description', $meta->seo_description)
                ->setMetaValue('open_graph_type', $meta->open_graph_type)
                ->setMetaValue('image', $meta->seo_image);
        }
    }

    /**
     * Set a meta value for the given key.
     *
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function setMetaValue(string $key, string $value): self
    {
        $this->meta->put($key, $value);

        return $this;
    }

    /**
     * Generate all of the SEO meta tags.
     *
     * @return string
     */
    public function tags(): string
    {
        $this->loadMetaFromDatabase();

        return sprintf(
            '%s%s%s%s%s%s%s%s%s%s%s%s',
            sprintf(self::$templates['charset'], $this->meta->get('charset'), $this->meta->get('charset')),
            sprintf(self::$templates['title'], $this->getTitleValue()),
            sprintf(self::$templates['common'], 'title', $this->getTitleValue()),
            sprintf(self::$templates['common'], 'description', $this->meta->get('description')),
            PHP_EOL,
            $this->getOpenGraphTags(),
            PHP_EOL,
            $this->getTwitterCardTags(),
            PHP_EOL,
            sprintf(self::$templates['common'], 'viewport', $this->meta->get('viewport')),
            sprintf(self::$templates['common'], 'robots', $this->meta->get('robots')),
            PHP_EOL
        );
    }
}
