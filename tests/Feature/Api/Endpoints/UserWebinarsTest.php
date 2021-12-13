<?php

namespace Tests\Feature\Api\Endpoints;

use App\Models\User;
use App\Models\UserWebinar;
use App\Models\Webinar;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;
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
     * Variable to store webinar model
     *
     * @var Webinar
     */
    protected $webinar;

    /**
     * Current endpoint url which being tested.
     *
     * @var string
     */
    protected $endpoint = '/api/user_webinars/';

    /**
     * Setup the test environment.
     *
     * return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(['PermissionSeeder', 'RoleSeeder']);

        $this->webinar = Webinar::factory()->create();

        $this->user = User::factory()->create();
        $this->user->webinars()->attach($this->webinar->id);

        $this->actingAs($this->user);
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'user_id'           => (string) $this->user->getAttribute('id'),
                'webinar_id'        => (string) $this->webinar->getAttribute('id'),
                'payment_status'    => UserWebinar::PAYMENT_IN_PROGRESS,
                'payment_method'    => null,
                'feedback'          => null,
                'payment_token'     => null,
            ]);
    }

    /** @test */
    public function show_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.$this->webinar->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'user_id'           => (string) $this->user->getAttribute('id'),
                'webinar_id'        => (string) $this->webinar->getAttribute('id'),
                'payment_status'    => UserWebinar::PAYMENT_IN_PROGRESS,
                'payment_method'    => null,
                'feedback'          => null,
                'payment_token'     => null,
            ]);
    }

    /** @test */
    public function create_endpoint_works_as_expected()
    {
        // Submitted data
        $webinar = Webinar::factory()->create();
        $data = UserWebinar::factory([
            'webinar_id' => $webinar->id
        ])->raw();

        if (!isset($webinar['type'])  || $webinar['type'] === Webinar::TYPE_FREE) {
            $webinar->update([
                'price' => 1,
                'type' => Webinar::TYPE_PAID,
            ]);
        }

        // The data which should be shown
        $seenData = $data;
        $seenData['payment_status'] = UserWebinar::PAYMENT_IN_PROGRESS;
        $seenData['user_id']        = $this->user->id;
        $seenData['webinar_id']     = $data['webinar_id'];

        $this->postJson($this->endpoint, $data)
            ->assertStatus(201)
            ->assertJsonFragment($seenData);

        $this->assertDatabaseHas('webinars', [
            'id'            => $data['webinar_id'],
            'participants'  => 1,
        ]);
    }

    /** @test */
    public function user_cant_register_to_an_already_registered_webinar()
    {
        // Submitted data
        $data = [
            'webinar_id' => $this->webinar->id,
        ];

        // The data which should be shown
        $seenData = $data;

        $this->postJson($this->endpoint, $data)
            ->assertStatus(422)
            ->assertJsonMissing($seenData);
    }

    /** @test */
    public function auto_accept_payment_on_free_webinar()
    {
        // Submitted data
        $webinar = Webinar::factory()->create();
        $data = UserWebinar::factory([
            'webinar_id' => $webinar->id
        ])->raw();

        if (!isset($webinar['type'])  || $webinar['type'] === Webinar::TYPE_PAID) {
            $webinar->update([
                'price' => 0,
                'type' => Webinar::TYPE_FREE,
            ]);
        }

        // The data which should be shown
        $seenData = $data;
        $seenData['payment_status'] = UserWebinar::PAYMENT_SUCCESS;
        $seenData['user_id']        = $this->user->id;
        $seenData['webinar_id']     = $data['webinar_id'];

        $this->postJson($this->endpoint, $data)
            ->assertStatus(201)
            ->assertJsonFragment($seenData);

        $this->assertDatabaseHas('webinars', [
            'id'            => $data['webinar_id'],
            'participants'  => 1,
        ]);
    }


    /** @test */
    public function delete_endpoint_works_as_expected()
    {
        $this->deleteJson($this->endpoint.$this->webinar->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'info' => 'The user webinar has been deleted.',
            ]);

        $this->assertDatabaseMissing('user_webinar', [
            'user_id'           => (string) $this->user->getAttribute('id'),
            'webinar_id'        => (string) $this->webinar->getAttribute('id'),
            'payment_status'    => UserWebinar::PAYMENT_IN_PROGRESS,
            'payment_method'    => null,
            'feedback'          => null,
            'payment_token'     => null,
        ]);

        $this->assertDatabaseHas('webinars', [
            'id'            => $this->webinar->getKey(),
            'participants'  => 0,
        ]);
    }
}
