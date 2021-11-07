<?php

namespace Tests\Feature\Api\Endpoints;

use App\Models\User;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UsersTest extends TestCase
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
    protected $endpoint = '/api/users/';

    /**
     * Faker generator instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * The model which being tested.
     *
     * @var User
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
//        $this->user = User::factory()->create()->assignRole('super-administrator');

//        $this->actingAs($this->user, config('api.cms_guard'));

        $this->model = User::factory()->create();
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'email' => $this->model->getAttribute('email'),
                'name' => $this->model->getAttribute('name'),
                'phone_number' => $this->model->getAttribute('phone_number'),
            ]);
    }

    /** @test */
    public function show_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'email' => $this->model->getAttribute('email'),
                'name' => $this->model->getAttribute('name'),
                'phone_number' => $this->model->getAttribute('phone_number'),
            ]);
    }

    /** @test */
    public function create_endpoint_works_as_expected()
    {
        // Submitted data
        $data = User::factory()->raw();

        // The data which should be shown
        $seenData = $data;
        unset($seenData['password']);

        $this->postJson($this->endpoint, $data)
            ->assertStatus(201)
            ->assertJsonFragment($seenData);
    }

    /** @test */
    public function update_endpoint_works_as_expected()
    {
        // Submitted data
        $data = User::factory()->raw();

        // The data which should be shown
        $seenData = $data;
        unset($seenData['password']);

        $this->patchJson($this->endpoint.$this->model->getKey(), $data)
            ->assertStatus(200)
            ->assertJsonFragment($seenData);
    }

    /** @test */
    public function delete_endpoint_works_as_expected()
    {
        $this->assertDatabaseHas('users', [
            'email' => $this->model->getAttribute('email'),
            'password' => $this->model->getAttribute('password'),
            'name' => $this->model->getAttribute('name'),
            'phone_number' => $this->model->getAttribute('phone_number'),
        ]);

        $this->deleteJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'info' => 'The user has been deleted.',
            ]);

        $this->assertDatabaseMissing('users', [
            'email' => $this->model->getAttribute('email'),
            'password' => $this->model->getAttribute('password'),
            'name' => $this->model->getAttribute('name'),
            'phone_number' => $this->model->getAttribute('phone_number'),
            'deleted_at' => null,
        ]);
    }
}
