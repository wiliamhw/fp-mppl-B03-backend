<?php

namespace Tests\Feature\Api\Endpoints;

use App\Models\User;
use App\Models\Webinar;
use DateTimeInterface;
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
        $this->model = Webinar::factory()->create([
            'published_at' => now()
        ]);
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param DateTimeInterface $date
     *
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i');
    }

    private function getWebinarContents(): array
    {
        return [
            'category_id'       => $this->getCastedAttribute('category_id'),
            'title'             => $this->getCastedAttribute('title'),
            'brief_description' => $this->getCastedAttribute('brief_description'),
            'start_at'          => $this->getCastedAttribute('start_at'),
            'end_at'            => $this->getCastedAttribute('end_at'),
            'price'             => $this->getCastedAttribute('price'),
            'type'              => $this->getCastedAttribute('type'),
            'status'            => $this->getCastedAttribute('status'),
            'participants'      => $this->getCastedAttribute('participants'),
        ];
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.'?append=status')
            ->assertStatus(200)
            ->assertJsonFragment($this->getWebinarContents());
    }

    /** @test */
    public function index_endpoint_wont_show_unpublished_webinars()
    {
        $this->model->update(['published_at' => null]);
        $this->getJson($this->endpoint.'?append=status')
            ->assertStatus(200)
            ->assertJsonMissing($this->getWebinarContents());
    }

    /** @test */
    public function show_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.$this->model->getKey().'?append=status')
            ->assertStatus(200)
            ->assertJsonFragment($this->getWebinarContents());
    }
}
