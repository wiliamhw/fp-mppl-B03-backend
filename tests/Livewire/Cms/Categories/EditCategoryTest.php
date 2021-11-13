<?php

namespace Tests\Livewire\Cms\Categories;

use App\Models\Admin;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class EditCategoryTest extends TestCase
{
    use CmsTests;
    use DatabaseMigrations;

    /**
     * Cms Admin Object.
     *
     * @var \App\Models\Admin
     */
    protected Admin $admin;

    /**
     * The Category instance to support any test cases.
     *
     * @var Category
     */
    protected Category $category;

    /**
     * Setup the test environment.
     *
     * return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(['PermissionSeeder', 'RoleSeeder']);

        $this->admin = Admin::factory()->create()->assignRole('super-administrator');

        $this->actingAs($this->admin, config('cms.guard'));

        $this->category = Category::factory()->create();
    }

    /** @test */
    public function edit_component_is_accessible()
    {
        Livewire::test('cms.categories.edit-category', ['category' => $this->category])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_update_the_existing_category_record()
    {
        $data = $this->fakeRawData(Category::class);

        Livewire::test('cms.categories.edit-category', ['category' => $this->category])
            ->set('category.name', $data['name'])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/categories');

        $this->assertDatabaseHas('categories', $data);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The category has been updated.', session('alertMessage'));
    }

    /** @test */
    public function it_can_cancel_updating_existing_category_and_go_back_to_index_page()
    {
        $data = $this->fakeRawData(Category::class);

        Livewire::test('cms.categories.edit-category', ['category' => $this->category])
            ->set('category.name', $data['name'])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/categories');

        $this->assertDatabaseMissing('categories', $data);
    }
}
