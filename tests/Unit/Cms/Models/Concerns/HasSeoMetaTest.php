<?php

namespace Tests\Unit\Cms\Models\Concerns;

use App\Models\SeoMeta;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\App;
use Tests\Support\Database\Models\Post;
use Tests\TestCase;

class HasSeoMetaTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * The post model instance.
     *
     * @var Post
     */
    protected Post $post;

    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $migrationPath = 'tests/Support/Database/Migrations';
        $this->artisan('migrate --path='.$migrationPath);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback --step=5');

            RefreshDatabaseState::$migrated = false;
        });

        $this->post = Post::factory()->create();
        SeoMeta::factory()->create([
            'attachable_type' => Post::class,
            'attachable_id'   => $this->post->getKey(),
            'locale'          => 'en',
            'seo_url'         => null,
            'seo_content'     => null,
        ]);
        SeoMeta::factory()->create([
            'attachable_type' => Post::class,
            'attachable_id'   => $this->post->getKey(),
            'locale'          => 'id',
            'seo_url'         => null,
            'seo_content'     => null,
        ]);
    }

    /** @test */
    public function it_can_retrieve_all_of_the_related_seo_meta_records()
    {
        $seoMetas = $this->post->getAttribute('seoMetas');

        self::assertEquals(2, $seoMetas->count());
    }

    /** @test */
    public function it_can_retrieve_the_english_seo_meta_record()
    {
        App::setLocale('en');
        $expected = SeoMeta::findOrFail(1)->toArray();
        $actual = $this->post->getAttribute('seo_meta')->toArray();
        unset($actual['media']);

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_retrieve_the_indonesian_seo_meta_record_with_direct_accessor_method()
    {
        App::setLocale('en');
        $expected = SeoMeta::findOrFail(2)->toArray();
        $actual = $this->post->getSeoMetaAttribute('id')->toArray();
        unset($actual['media']);

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_create_new_seo_meta_when_there_is_no_matched_seo_meta_available()
    {
        App::setLocale('en');
        $post = Post::factory()->create();
        $seoMeta = $post->getAttribute('seo_meta');

        self::assertInstanceOf(SeoMeta::class, $seoMeta);
        self::assertFalse($seoMeta->exists);
        self::assertEquals(get_class($post), $seoMeta->getAttribute('attachable_type'));
        self::assertEquals($post->getKey(), $seoMeta->getAttribute('attachable_id'));
        self::assertEquals('en', $seoMeta->getAttribute('locale'));
    }

    /** @test */
    public function it_can_perform_join_clause_with_seo_metas_table()
    {
        $expected = 'select "posts".*, "seo_metas"."seo_url", "seo_metas"."seo_title", "seo_metas"."seo_description", "seo_metas"."seo_content", "seo_metas"."open_graph_type" from "posts" left join "seo_metas" on "seo_metas"."attachable_id" = "posts"."id" where "seo_metas"."attachable_type" = ? and "seo_metas"."locale" = ? and "seo_metas"."deleted_at" is null and "posts"."deleted_at" is null order by "id" desc';
        $actual = Post::joinSeoMeta()->orderBy('id', 'desc')->toSql();

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_perform_join_query_with_seo_metas_table()
    {
        App::setLocale('en');
        $seoMeta = SeoMeta::findOrFail(1);

        $expected = array_merge($this->post->toArray(), [
            'seo_title'       => data_get($seoMeta, 'seo_title'),
            'seo_description' => data_get($seoMeta, 'seo_description'),
            'open_graph_type' => data_get($seoMeta, 'open_graph_type'),
            'deleted_at'      => null,
            'seo_url'         => '',
            'seo_content'     => null,
        ]);

        $actual = Post::joinSeoMeta()->orderBy('id', 'desc')->first()->toArray();
        unset($actual['seo_metas']);

        self::assertEquals($expected, $actual);
    }
}
