<?php

namespace App\Http\Livewire\Cms\Categories;

use App\Models\Admin;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class ShowCategoryTest extends TestCase
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
    public function show_component_is_accessible()
    {
        Livewire::test('cms.categories.show-category', ['category' => $this->category])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_guide_admin_to_the_edit_category_page()
    {
        Livewire::test('cms.categories.show-category', ['category' => $this->category])
            ->call('edit')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/categories/'. $this->category->getKey() .'/edit');
    }

    /** @test */
    public function it_can_go_back_to_index_page()
    {
        Livewire::test('cms.categories.show-category', ['category' => $this->category])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/categories');
    }
}
