<?php

namespace Tests\Feature\Api\Endpoints;

use App\Models\User;
use App\Models\Category;
use App\Models\Webinar;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoriesTest extends TestCase
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
    protected $endpoint = '/api/categories/';

    /**
     * Faker generator instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * The model which being tested.
     *
     * @var Category
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
        $this->model = Category::factory()->create();
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        Webinar::factory()->create([
            'category_id' => 1
        ]);
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => $this->model->getAttribute('name'),
            ]);
    }

    /** @test */
    public function index_endpoint_wont_show_categories_that_doesnt_have_webinar()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonMissing([
                'name' => $this->model->getAttribute('name'),
            ]);
    }
}
