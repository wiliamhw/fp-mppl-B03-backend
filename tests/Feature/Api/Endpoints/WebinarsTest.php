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
    protected $endpoint = '/api/webinars?append=status/';

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
        $this->model = Webinar::factory()->create([
            'published_at' => now()
        ]);
    }

    private function getWebinarContents(): array
    {
        return [
            'category_id'       => $this->getCastedAttribute('category_id'),
            'title'             => $this->getCastedAttribute('title'),
            'description'       => $this->getCastedAttribute('description'),
            'start_at'          => $this->getCastedAttribute('start_at'),
            'end_at'            => $this->getCastedAttribute('end_at'),
            'price'             => $this->getCastedAttribute('price'),
            'type'              => $this->getCastedAttribute('type'),
            'zoom_id'           => $this->getCastedAttribute('zoom_id'),
            'max_participants'  => $this->getCastedAttribute('max_participants'),
            'status'            => $this->getCastedAttribute('status'),
        ];
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment($this->getWebinarContents());
    }

    /** @test */
    public function index_endpoint_wont_show_unpublished_webinars()
    {
        $this->model->update(['published_at' => null]);
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonMissing($this->getWebinarContents());
    }
}
