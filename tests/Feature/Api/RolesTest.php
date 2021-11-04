<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolesTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Currently logged in user.
     *
     * @var User
     */
    protected $user;

    /**
     * Current endpoint url which being tested.
     *
     * @var string
     */
    protected $endpoint = '/api/roles/';

    /**
     * Faker generator instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * The model which being tested.
     *
     * @var Role
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

        Role::findOrCreate('role-1', config('api.cms_guard'));
        Role::findOrCreate('role-2', config('api.cms_guard'));

        $this->model = Role::findOrFail(2);
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'name'       => $this->model->getAttribute('name'),
                'guard_name' => $this->model->getAttribute('guard_name'),
            ]);
    }

    /** @test */
    public function show_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'name'       => $this->model->getAttribute('name'),
                'guard_name' => $this->model->getAttribute('guard_name'),
            ]);
    }
}
