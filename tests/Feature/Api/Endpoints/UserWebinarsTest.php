<?php

namespace Tests\Feature\Api\Endpoints;

use App\Models\User;
use App\Models\UserWebinar;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserWebinarsTest extends TestCase
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
    protected $endpoint = '/api/user_webinars/';

    /**
     * Faker generator instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * The model which being tested.
     *
     * @var UserWebinar
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
        $this->user = User::factory()->create()->assignRole('super-administrator');

        $this->actingAs($this->user, config('api.cms_guard'));

        $this->model = UserWebinar::factory()->create();
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'user_id' => $this->model->getAttribute('user_id'),
                'webinar_id' => $this->model->getAttribute('webinar_id'),
                'payment_status' => $this->model->getAttribute('payment_status'),
                'payment_method' => $this->model->getAttribute('payment_method'),
                'feedback' => $this->model->getAttribute('feedback'),
                'payment_token' => $this->model->getAttribute('payment_token'),
            ]);
    }

    /** @test */
    public function show_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'user_id' => $this->model->getAttribute('user_id'),
                'webinar_id' => $this->model->getAttribute('webinar_id'),
                'payment_status' => $this->model->getAttribute('payment_status'),
                'payment_method' => $this->model->getAttribute('payment_method'),
                'feedback' => $this->model->getAttribute('feedback'),
                'payment_token' => $this->model->getAttribute('payment_token'),
            ]);
    }

    /** @test */
    public function create_endpoint_works_as_expected()
    {
        // Submitted data
        $data = UserWebinar::factory()->raw();

        // The data which should be shown
        $seenData = $data;

        $this->postJson($this->endpoint, $data)
            ->assertStatus(201)
            ->assertJsonFragment($seenData);
    }

    /** @test */
    public function update_endpoint_works_as_expected()
    {
        // Submitted data
        $data = UserWebinar::factory()->raw();

        // The data which should be shown
        $seenData = $data;

        $this->patchJson($this->endpoint.$this->model->getKey(), $data)
            ->assertStatus(200)
            ->assertJsonFragment($seenData);
    }

    /** @test */
    public function delete_endpoint_works_as_expected()
    {
        $this->deleteJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'info' => 'The user webinar has been deleted.',
            ]);

        $this->assertDatabaseHas('user_webinars', [
            'user_id' => $this->model->getAttribute('user_id'),
            'webinar_id' => $this->model->getAttribute('webinar_id'),
            'payment_status' => $this->model->getAttribute('payment_status'),
            'payment_method' => $this->model->getAttribute('payment_method'),
            'feedback' => $this->model->getAttribute('feedback'),
            'payment_token' => $this->model->getAttribute('payment_token'),
        ]);

        $this->assertDatabaseMissing('user_webinars', [
            'user_id' => $this->model->getAttribute('user_id'),
            'webinar_id' => $this->model->getAttribute('webinar_id'),
            'payment_status' => $this->model->getAttribute('payment_status'),
            'payment_method' => $this->model->getAttribute('payment_method'),
            'feedback' => $this->model->getAttribute('feedback'),
            'payment_token' => $this->model->getAttribute('payment_token'),
            'deleted_at' => null,
        ]);
    }
}
