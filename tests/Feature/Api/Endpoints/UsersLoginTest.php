<?php

namespace Tests\Feature\Api\Endpoints;

use App\Models\User;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UsersLoginTest extends TestCase
{
    use DatabaseMigrations;

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
     * The user which being tested.
     *
     * @var User
     */
    protected $user;

    /**
     * Setup the test environment.
     *
     * return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(['PermissionSeeder', 'RoleSeeder']);
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function login_endpoint_works_as_expected()
    {
        $data = [
            'email'     => $this->user->getAttribute('email'),
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
//        $data = [
//            'email'     => $this->user->getAttribute('email'),
//            'password'  => 'password'
//        ];
//        $response = $this->postJson($this->endpoint.'login', $data);

        $this->getJson($this->endpoint.'logout', [
            'Authorization' => auth()->user()->currentAccessToken()->token
        ])->assertStatus(200)
            ->assertJsonFragment([
                'message' => 'Token Revoked',
                'code'  => 200
            ]);
    }

    /** @test */
    public function sanctum_middleware_block_authenticated_user()
    {
        $this->getJson('/api/test/login')
            ->assertStatus(401);
    }
}
