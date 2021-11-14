<?php

namespace Tests\Feature\Api\Endpoints;

use App\Models\User;
use App\Models\Webinar;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class WebinarsTest extends TestCase
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
    protected $endpoint = '/api/webinars/';

    /**
     * Faker generator instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * The model which being tested.
     *
     * @var Webinar
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

        $this->faker = new Generator();
        $this->user = User::factory()->create()->assignRole('super-administrator');

        $this->actingAs($this->user, config('api.cms_guard'));

        $this->model = Webinar::factory()->create();
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'category_id' => $this->model->getAttribute('category_id'),
                'title' => $this->model->getAttribute('title'),
                'description' => $this->model->getAttribute('description'),
                'start_at' => $this->model->getAttribute('start_at'),
                'end_at' => $this->model->getAttribute('end_at'),
                'price' => $this->model->getAttribute('price'),
                'zoom_id' => $this->model->getAttribute('zoom_id'),
                'max_participants' => $this->model->getAttribute('max_participants'),
                'partner_name' => $this->model->getAttribute('partner_name'),
                'published_at' => $this->model->getAttribute('published_at'),
            ]);
    }
}
