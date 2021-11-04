<?php

namespace Tests;

use Illuminate\Support\Str;

trait CmsTests
{
    /**
     * Get base route url.
     *
     * @param string $config
     *
     * @return string
     */
    public function getBaseRouteUrl(string $config): string
    {
        $url = (string) config('cms.'.$config);

        if (Str::endsWith($url, '/')) {
            $url = substr($url, 0, -1);
        }

        return $url;
    }

    /**
     * Get Auth Url.
     *
     * @param string $url
     *
     * @return string
     */
    public function getAuthUrl(string $url): string
    {
        if (!Str::startsWith($url, '/')) {
            $url = '/'.$url;
        }

        return $this->getBaseRouteUrl('auth_path_prefix').$url;
    }

    /**
     * Get CMS Url.
     *
     * @param string $url
     *
     * @return string
     */
    public function getCmsUrl(string $url): string
    {
        if (!Str::startsWith($url, '/')) {
            $url = '/'.$url;
        }

        return $this->getBaseRouteUrl('path_prefix').$url;
    }
}
