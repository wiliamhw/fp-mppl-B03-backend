<?php

return [

    /**
     * The default character encoding for the HTML document.
     */
    'charset' => 'utf-8',

    /**
     * The default title for the HTML document. This value will be used whenever the SEO service
     * failed to retrieve SEO title from database.
     */
    'title' => 'Laravel Application',

    /**
     * The default description for the HTML document. This value will be used whenever the SEO service
     * failed to retrieve SEO description from database.
     */
    'description' => '',

    /**
     * The default image for social meta tags of the HTML document. This value will be used whenever
     * the SEO service failed to retrieve SEO image from database.
     */
    'image' => 'https://picsum.photos/1200/630',

    /**
     * Specify the website name to be mixed into the end of the page title.
     */
    'website_name' => env('APP_NAME', 'Laravel'),

    /**
     * The default open graph type of the HTML document.
     */
    'open_graph_type' => 'website',

    /**
     * Specify the Facebook App ID which would let Facebook know the identify of your site,
     * and provides additional benefits like social analytics, comments moderation and
     * authentication capabilities to your site.
     */
    'facebook_app_id' => env('FACEBOOK_APP_ID'),

    /**
     * Specify the Twitter account that created the content within the card.
     */
    'twitter_account' => env('TWITTER_ACCOUNT'),

    /**
     * Specify the default Twitter card type, which will be one of these:
     * “summary”, “summary_large_image”, “app”, or “player”.
     */
    'twitter_card_type' => 'summary_large_image',

    /**
     * The default instruction for crawler robots for how to crawl
     * the website's pages, which will be a subset of these parameters:.
     *
     *   "index"         - Tells the search engine to index the page.
     *   "noindex"       - Tells the search engine not to index the page.
     *   "follow"        - Tells the crawlers to follow all the links on the page, even if the page isn’t indexed.
     *   "nofollow"      - Tells the crawlers not to follow any links on the page or pass along any link equity.
     *   "noimageindex"  - Tells the crawlers not to index any images on the page.
     *   "none"          - Equivalent to using both the noindex and nofollow tags simultaneously.
     *   "noarchive"     - Search engines should not show a cached link to the page on a SERP.
     *   "nosnippet"     - Search engine should not show a snippet of the page (i.e. meta description) of the page on a SERP.
     */
    'robots' => 'index, follow',

    /**
     * Viewport meta tag gives the browser instructions on how to control the page's dimensions and scaling.
     * And based on the content of viewport tag, it also tells the search engine that a web page is mobile friendly.
     */
    'viewport' => 'width=device-width, initial-scale=1.0',

    /**
     * Define whether the page title should be mixed with the website name. The mixed title will be following this format:
     *  "Page Title - Website Name".
     */
    'mix_title_with_website_name' => true,

];
