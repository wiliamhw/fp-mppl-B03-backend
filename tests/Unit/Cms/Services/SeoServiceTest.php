<?php

namespace Tests\Unit\Cms\Services;

use App\Models\SeoMeta;
use Cms\Services\SeoService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Tests\CmsTests;
use Tests\TestCase;

class SeoServiceTest extends TestCase
{
    use CmsTests;
    use DatabaseMigrations;

    /**
     * SEO Service object.
     *
     * @var \Cms\Services\SeoService
     */
    protected SeoService $service;

    /**
     * Set Up the test environment and requirement.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new SeoService();
    }

    /** @test */
    public function it_can_set_and_get_the_meta_values()
    {
        $expected = 'LuminousCMS';
        $this->service->setMetaValue('title', $expected);

        $this->assertEquals($expected, $this->service->getMetaValue('title'));
    }

    /** @test */
    public function it_can_mix_title_meta_content_with_website_name()
    {
        $expected = 'Laravel Application - Laravel';
        $actual = $this->invokeMethod($this->service, 'getTitleValue');

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_mix_title_meta_content_without_website_name()
    {
        $this->app['config']->set('seo.mix_title_with_website_name', false);

        $expected = 'Laravel Application';
        $actual = $this->invokeMethod($this->service, 'getTitleValue');

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_generate_open_graph_meta_tags_with_facebook_app_id()
    {
        $this->service->setMetaValue('facebook_app_id', '884867981620471');
        $this->service->setMetaValue('description', 'Laravel is a web application framework with expressive, elegant syntax. We’ve already laid the foundation');

        $expected = '<meta property="fb:app_id" content="884867981620471">'.PHP_EOL.
            '<meta property="og:type" content="website">'.PHP_EOL.
            '<meta property="og:url" content="http://localhost">'.PHP_EOL.
            '<meta property="og:title" content="Laravel Application - Laravel">'.PHP_EOL.
            '<meta property="og:description" content="Laravel is a web application framework with expressive, elegant syntax. We’ve already laid the foundation">'.PHP_EOL.
            '<meta property="og:image" content="https://picsum.photos/1200/630">'.PHP_EOL;

        $actual = $this->invokeMethod($this->service, 'getOpenGraphTags');

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_generate_open_graph_meta_tags_without_facebook_app_id()
    {
        $this->service->setMetaValue('description', 'Laravel is a web application framework with expressive, elegant syntax. We’ve already laid the foundation');

        $expected = '<meta property="og:type" content="website">'.PHP_EOL.
            '<meta property="og:url" content="http://localhost">'.PHP_EOL.
            '<meta property="og:title" content="Laravel Application - Laravel">'.PHP_EOL.
            '<meta property="og:description" content="Laravel is a web application framework with expressive, elegant syntax. We’ve already laid the foundation">'.PHP_EOL.
            '<meta property="og:image" content="https://picsum.photos/1200/630">'.PHP_EOL;

        $actual = $this->invokeMethod($this->service, 'getOpenGraphTags');

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_generate_twitter_card_tags_with_creator_tag()
    {
        $this->service->setMetaValue('twitter_account', '@RichanFongdasen');

        $expected = '<meta property="twitter:site" content="@RichanFongdasen">'.PHP_EOL.
            '<meta property="twitter:creator" content="@RichanFongdasen">'.PHP_EOL.
            '<meta property="twitter:card" content="summary_large_image">'.PHP_EOL.
            '<meta property="twitter:url" content="http://localhost">'.PHP_EOL.
            '<meta property="twitter:title" content="Laravel Application - Laravel">'.PHP_EOL.
            '<meta property="twitter:description" content="">'.PHP_EOL.
            '<meta property="twitter:image" content="https://picsum.photos/1200/630">'.PHP_EOL;

        $actual = $this->invokeMethod($this->service, 'getTwitterCardTags');

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_generate_twitter_card_tags_without_creator_tag()
    {
        $expected = '<meta property="twitter:card" content="summary_large_image">'.PHP_EOL.
            '<meta property="twitter:url" content="http://localhost">'.PHP_EOL.
            '<meta property="twitter:title" content="Laravel Application - Laravel">'.PHP_EOL.
            '<meta property="twitter:description" content="">'.PHP_EOL.
            '<meta property="twitter:image" content="https://picsum.photos/1200/630">'.PHP_EOL;

        $actual = $this->invokeMethod($this->service, 'getTwitterCardTags');

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_generate_all_of_seo_meta_tags()
    {
        $this->service->setMetaValue('description', 'Laravel is a web application framework with expressive, elegant syntax. We’ve already laid the foundation');

        $expected = '<meta charset="utf-8">'.PHP_EOL.
            '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'.PHP_EOL.
            '<title>Laravel Application - Laravel</title>'.PHP_EOL.
            '<meta name="title" content="Laravel Application - Laravel">'.PHP_EOL.
            '<meta name="description" content="Laravel is a web application framework with expressive, elegant syntax. We’ve already laid the foundation">'.PHP_EOL.PHP_EOL.

            '<meta property="og:type" content="website">'.PHP_EOL.
            '<meta property="og:url" content="http://localhost">'.PHP_EOL.
            '<meta property="og:title" content="Laravel Application - Laravel">'.PHP_EOL.
            '<meta property="og:description" content="Laravel is a web application framework with expressive, elegant syntax. We’ve already laid the foundation">'.PHP_EOL.
            '<meta property="og:image" content="https://picsum.photos/1200/630">'.PHP_EOL.PHP_EOL.

            '<meta property="twitter:card" content="summary_large_image">'.PHP_EOL.
            '<meta property="twitter:url" content="http://localhost">'.PHP_EOL.
            '<meta property="twitter:title" content="Laravel Application - Laravel">'.PHP_EOL.
            '<meta property="twitter:description" content="Laravel is a web application framework with expressive, elegant syntax. We’ve already laid the foundation">'.PHP_EOL.
            '<meta property="twitter:image" content="https://picsum.photos/1200/630">'.PHP_EOL.PHP_EOL.

            '<meta name="viewport" content="width=device-width, initial-scale=1.0">'.PHP_EOL.
            '<meta name="robots" content="index, follow">'.PHP_EOL.PHP_EOL;

        $this->assertEquals($expected, $this->service->tags());
    }

    /** @test */
    public function it_can_generate_all_of_seo_meta_tags_from_database()
    {
        $this->service->setMetaValue('description', 'Laravel is a web application framework with expressive, elegant syntax. We’ve already laid the foundation');

        \Storage::fake('public');

        $fakeImage = UploadedFile::fake()
            ->image('seo_image.jpg', 1200, 630)
            ->size(390);

        $meta = new SeoMeta([
            'seo_url'         => '/',
            'seo_title'       => 'LuminousCMS',
            'seo_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua',
            'open_graph_type' => 'article',
        ]);
        $meta->save();
        $meta->addMedia($fakeImage)->toMediaCollection('seo_image')->getGeneratedConversions();

        $expected = '<meta charset="utf-8">'.PHP_EOL.
            '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'.PHP_EOL.
            '<title>LuminousCMS - Laravel</title>'.PHP_EOL.
            '<meta name="title" content="LuminousCMS - Laravel">'.PHP_EOL.
            '<meta name="description" content="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua">'.PHP_EOL.PHP_EOL.

            '<meta property="og:type" content="article">'.PHP_EOL.
            '<meta property="og:url" content="http://localhost">'.PHP_EOL.
            '<meta property="og:title" content="LuminousCMS - Laravel">'.PHP_EOL.
            '<meta property="og:description" content="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua">'.PHP_EOL.
            '<meta property="og:image" content="http://localhost/storage/1/conversions/seo_image-seo_image_large.jpg">'.PHP_EOL.PHP_EOL.

            '<meta property="twitter:card" content="summary_large_image">'.PHP_EOL.
            '<meta property="twitter:url" content="http://localhost">'.PHP_EOL.
            '<meta property="twitter:title" content="LuminousCMS - Laravel">'.PHP_EOL.
            '<meta property="twitter:description" content="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua">'.PHP_EOL.
            '<meta property="twitter:image" content="http://localhost/storage/1/conversions/seo_image-seo_image_large.jpg">'.PHP_EOL.PHP_EOL.

            '<meta name="viewport" content="width=device-width, initial-scale=1.0">'.PHP_EOL.
            '<meta name="robots" content="index, follow">'.PHP_EOL.PHP_EOL;

        $this->assertEquals($expected, $this->service->tags());
    }

    /** @test */
    public function it_can_generate_all_of_seo_meta_tags_from_database_with_default_image()
    {
        $this->service->setMetaValue('description', 'Laravel is a web application framework with expressive, elegant syntax. We’ve already laid the foundation');

        $meta = new SeoMeta([
            'seo_url'         => '/',
            'seo_title'       => 'LuminousCMS',
            'seo_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua',
            'open_graph_type' => 'article',
        ]);
        $meta->save();

        $expected = '<meta charset="utf-8">'.PHP_EOL.
            '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'.PHP_EOL.
            '<title>LuminousCMS - Laravel</title>'.PHP_EOL.
            '<meta name="title" content="LuminousCMS - Laravel">'.PHP_EOL.
            '<meta name="description" content="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua">'.PHP_EOL.PHP_EOL.

            '<meta property="og:type" content="article">'.PHP_EOL.
            '<meta property="og:url" content="http://localhost">'.PHP_EOL.
            '<meta property="og:title" content="LuminousCMS - Laravel">'.PHP_EOL.
            '<meta property="og:description" content="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua">'.PHP_EOL.
            '<meta property="og:image" content="https://picsum.photos/1200/630">'.PHP_EOL.PHP_EOL.

            '<meta property="twitter:card" content="summary_large_image">'.PHP_EOL.
            '<meta property="twitter:url" content="http://localhost">'.PHP_EOL.
            '<meta property="twitter:title" content="LuminousCMS - Laravel">'.PHP_EOL.
            '<meta property="twitter:description" content="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua">'.PHP_EOL.
            '<meta property="twitter:image" content="https://picsum.photos/1200/630">'.PHP_EOL.PHP_EOL.

            '<meta name="viewport" content="width=device-width, initial-scale=1.0">'.PHP_EOL.
            '<meta name="robots" content="index, follow">'.PHP_EOL.PHP_EOL;

        $this->assertEquals($expected, $this->service->tags());
    }
}
