<?php

namespace Tests\Feature\Api;

use App\Models\Admin;
use App\Models\User;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AdminsTest extends TestCase
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
    protected $endpoint = '/api/admins/';

    /**
     * Faker generator instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * The model which being tested.
     *
     * @var Admin
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

        Admin::factory()->count(3)->create();
        $this->model = Admin::findOrFail(2);
    }

    /** @test */
    public function api_access_rejected_without_json_support()
    {
        $this->get($this->endpoint)
            ->assertStatus(405)
            ->assertJsonFragment(['message' => 'Backend accept only json communication.']);
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'name'  => $this->model->getAttribute('name'),
                'email' => $this->model->getAttribute('email'),
            ]);
    }

    /** @test */
    public function show_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'name'  => $this->model->getAttribute('name'),
                'email' => $this->model->getAttribute('email'),
            ]);
    }
}
