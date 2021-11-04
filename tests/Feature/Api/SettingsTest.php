<?php

namespace Tests\Feature\Api;

use App\Models\Setting;
use App\Models\User;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SettingsTest extends TestCase
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
    protected $endpoint = '/api/settings/';

    /**
     * Faker generator instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * The model which being tested.
     *
     * @var Setting
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

        Setting::factory()->count(3)->create();
        $this->model = Setting::findOrFail(2);
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'type'  => $this->model->getAttribute('type'),
                'key'   => $this->model->getAttribute('key'),
                'value' => $this->model->getAttribute('value'),
            ]);
    }

    /** @test */
    public function show_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'type'  => $this->model->getAttribute('type'),
                'key'   => $this->model->getAttribute('key'),
                'value' => $this->model->getAttribute('value'),
            ]);
    }
}
