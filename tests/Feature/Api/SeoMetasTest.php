<?php

namespace Tests\Feature\Api;

use App\Models\SeoMeta;
use App\Models\User;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SeoMetasTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Currently logged in User.
     *
     * @var User
     */
    protected $user;

    /**
     * Current endpoint url which being tested.
     *
     * @var string
     */
    protected $endpoint = '/api/seo_metas/';

    /**
     * Faker generator instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * The model which being tested.
     *
     * @var SeoMeta
     */
    protected $model;

    /**
     * Setup the test environment.
     *
     * return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(['PermissionSeeder', 'RoleSeeder']);

        $this->faker = new Generator();
        $this->user = User::factory()->create();

        $this->actingAs($this->user);

        SeoMeta::factory()->count(3)->create();
        $this->model = SeoMeta::findOrFail(2);
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'attachable_type' => $this->model->getAttribute('model'),
                'attachable_id'   => $this->model->getAttribute('foreign_key'),
                'locale'          => $this->model->getAttribute('locale'),
                'seo_url'         => $this->model->getAttribute('seo_url'),
                'seo_title'       => $this->model->getAttribute('seo_title'),
                'seo_description' => $this->model->getAttribute('seo_description'),
                'open_graph_type' => $this->model->getAttribute('open_graph_type'),
            ]);
    }

    /** @test */
    public function show_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'attachable_type' => $this->model->getAttribute('model'),
                'attachable_id'   => $this->model->getAttribute('foreign_key'),
                'locale'          => $this->model->getAttribute('locale'),
                'seo_url'         => $this->model->getAttribute('seo_url'),
                'seo_title'       => $this->model->getAttribute('seo_title'),
                'seo_description' => $this->model->getAttribute('seo_description'),
                'open_graph_type' => $this->model->getAttribute('open_graph_type'),
            ]);
    }
}
