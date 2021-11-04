<?php

namespace Cms\Http\Middleware;

use Closure;
use Cms\Services\SeoService;

class BlockRobots
{
    /**
     * @var \Cms\Services\SeoService
     */
    protected $seoService;

    /**
     * BlockRobots constructor.
     */
    public function __construct()
    {
        $this->seoService = app(SeoService::class);
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->seoService->setMetaValue('title', config('seo.website_name'))
            ->setMetaValue('url', url('/'))
            ->setMetaValue('image', '')
            ->setMetaValue('description', '')
            ->setMetaValue('website_name', config('cms.name'))
            ->setMetaValue('robots', 'noindex, nofollow, noimageindex, noarchive, nosnippet')
            ->setMetaValue('viewport', 'width=device-width, initial-scale=1, shrink-to-fit=no');

        $response = $next($request);

        $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noimageindex, noarchive, nosnippet');

        return $response;
    }
}
