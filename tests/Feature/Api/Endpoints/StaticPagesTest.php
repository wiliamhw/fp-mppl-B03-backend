<?php

namespace Tests\Feature\Api\Endpoints;

use App\Models\StaticPage;
use App\Models\User;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class StaticPagesTest extends TestCase
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
    protected $endpoint = '/api/static_pages/';

    /**
     * Faker generator instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * The model which being tested.
     *
     * @var StaticPage
     */
    protected $model;

    /**
     * Setup the test environment.
     *
     * return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(['PermissionSeeder', 'RoleSeeder']);

        $this->faker = new Generator();
        $this->user = User::factory()->create();

        $this->actingAs($this->user, config('api.cms_guard'));

        $this->model = StaticPage::factory()->create();
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'name'          => $this->model->getAttribute('name'),
                'slug'          => $this->model->getAttribute('slug'),
                'content'       => $this->model->getAttribute('content'),
                'youtube_video' => $this->model->getAttribute('youtube_video'),
                'layout'        => $this->model->getAttribute('layout'),
                'published'     => $this->model->getAttribute('published'),
            ]);
    }

    /** @test */
    public function show_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'name'          => $this->model->getAttribute('name'),
                'slug'          => $this->model->getAttribute('slug'),
                'content'       => $this->model->getAttribute('content'),
                'youtube_video' => $this->model->getAttribute('youtube_video'),
                'layout'        => $this->model->getAttribute('layout'),
                'published'     => $this->model->getAttribute('published'),
            ]);
    }
}
