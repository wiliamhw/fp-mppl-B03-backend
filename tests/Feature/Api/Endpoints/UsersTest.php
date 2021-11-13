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

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        User::factory()->count(3)->create();
        $this->model = User::findOrFail(2);
    }

    /** @test */
    public function login_endpoint_works_as_expected()
    {
        $data = [
            'email'     => $this->model->getAttribute('email'),
            'password'  => 'password'
        ];
        $response = $this->postJson($this->endpoint.'login', $data);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code'      => 200,
                'status'    => 'OK',
            ])->assertJsonStructure([
                'data' => ['token']
            ]);
    }

    /** @test */
    public function show_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'email' => $this->user->getAttribute('email'),
                'name' => $this->user->getAttribute('name'),
                'phone_number' => $this->user->getAttribute('phone_number'),
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

        $this->assertDatabaseHas('users', $seenData);
    }

    /** @test */
    public function update_endpoint_works_as_expected()
    {
        // Submitted data
        $data = User::factory()->raw();

        // The data which should be shown
        $seenData = $data;
        unset($seenData['password']);

        $this->patchJson($this->endpoint, $data)
            ->assertStatus(200)
            ->assertJsonFragment($seenData);

        $this->assertDatabaseHas('users', $seenData);
    }

    /** @test */
    public function sanctum_middleware_allow_authenticated_user()
    {
        $data = [
            'email'     => $this->user->getAttribute('email'),
            'password'  => 'password'
        ];
        $response = $this->postJson($this->endpoint.'login', $data);

        $this->getJson('/api/test/login', [
            'Authorization' => $response['data']['token']
        ])->assertStatus(200)
            ->assertJsonFragment([
                'email' => $this->user->getAttribute('email'),
                'name'  => $this->user->getAttribute('name'),
                'phone_number' => $this->user->getAttribute('phone_number'),
            ]);
    }

    /** @test */
    public function logout_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.'logout')
            ->assertStatus(200)
            ->assertJsonFragment([
                'message' => 'Token Revoked',
                'code'  => 200
            ]);
    }
}
