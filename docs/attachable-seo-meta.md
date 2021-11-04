# Attachable SEO Meta (Feature)

## Table of contents

* [Configure Application Languages](#configure-application-languages)
* [Feature Implementation](#feature-implementation)
  * [Eloquent Model](#eloquent-model)
  * [Livewire Index Component](#livewire-index-component)
  * [Livewire Form Component](#livewire-form-component)
  * [Livewire Form Blade Template](#livewire-form-blade-template)
  * [API Related Design Patterns](#api-related-design-patterns)
* [Testing](#testing)

## Configure Application Languages

There are several configuration files that you need to check before implementing this feature. If you're planning to add multilingual support in your application, you need to configure these settings correctly. Because by default, the application support the English language only.

#### Laravel Application Locale Configuration

Configuration file path: `config/app.php`

```php
<?php

return [
    // ...
    
    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',
    
    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',
    
    // ...
];
```

#### Laravel I18n Package Configurations

Configuration file path: `config/i18n.php`

```php
<?php

return [
    // ...
    
    /*
    |--------------------------------------------------------------------------
    | Fallback language
    |--------------------------------------------------------------------------
    |
    | Define your preferred fallback language, which will be used when
    | Language Negotiator failed to recommend any supported language.
    |
    */

    'fallback_language' => 'en',
    
    // ...
];
```

Language repository file path: `storage/i18n/languages.json`

```json
[
    {
        "name":"English",
        "language":"en",
        "country":"US"
    }
]
```

Make sure you have added any additional languages that you need.

## Feature Implementation

### Eloquent Model

#### Model Setup

There are several changes that should be applied in your Eloquent Model class, such as:
* Implements `Cms\Contracts\SeoAttachedModel` interface.
* Use the `Cms\Models\Concerns\HasSeoMeta` trait in your model class.
* If you're using `soft deletes` feature in your model (you'd better be), you also need to apply these changes as well:
    * Use the `Dyrynda\Database\Support\CascadeSoftDeletes` trait in your model class.
    * Declare the protected `$cascadeDeletes` class property.

Please take a look at the example below:
```php
<?php

namespace App\Models;

use Cms\Contracts\SeoAttachedModel;
use Cms\Models\Concerns\HasSeoMeta;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model implements SeoAttachedModel
{
    use CascadeSoftDeletes;
    use HasSeoMeta;
    use SoftDeletes;

    /**
     * A collection of model relationships for cascading soft deletes automatically.
     *
     * @var string[]
     */
    protected $cascadeDeletes = [
        'seoMetas',
    ];
}
```

#### Leverage SEO Meta Relationship

You can utilize the SEO Meta relationship in your eloquent queries

```php
<?php

// Eager loads related SEO Meta records
Post::with('seoMetas')->append->limit(10)->get();

// Join SEO Meta model and perform query scopes.
Post::joinSeoMeta()->where('open_graph_type', 'article')->get();

```

### Livewire Index Component

If you intend to perform join query to the attached seo meta and display the information in `Livewire Datatable Component`, then you need to apply some changes in your datatable component.

Please take a look at the example below:
```php
<?php

namespace App\Http\Livewire\Cms\Posts;

use App\Models\Post;
use Cms\Livewire\DatatableColumn;
use Cms\Livewire\DatatableComponent;
use Illuminate\Database\Eloquent\Builder;

class PostsIndex extends DatatableComponent
{
    /**
     * Specify the datatable's columns and their behaviors.
     *
     * @return array
     */
    public function columns(): array
    {
        return $this->applyColumnVisibility([
            // ... Existing datatable columns definition
            
            // Register the joined seo_metas columns here
            DatatableColumn::make('seo_title')->setTitle('Title'),
            DatatableColumn::make('seo_description')->setTitle('Description'),
        ]);
    }

    /**
     * Get a new query builder instance for the current datatable component.
     * You may include the model's relationships if it's necessary.
     *
     * @return Builder
     */
    protected function newQuery(): Builder
    {
        return (new Post())
            ->newQuery()
            // Add scope joinSeoMeta here..
            ->joinSeoMeta();
    }

    /**
     * Specify the searchable column names in the current datatable component.
     *
     * @return array
     */
    protected function searchableColumns(): array
    {
        return [
            // ... Existing searchable columns definition
            
            // Add the joined seo_metas columns here
            'seo_title',
            'seo_description',
        ];
    }
}
```

### Livewire Form Component

There are some changes that should be implemented in your Livewire Form Component, such as:
* Using the `Cms\Livewire\Concerns\WithSeoMeta` trait in your Livewire component.
* Using the `Spatie\MediaLibraryPro\Http\Livewire\Concerns\WithMedia` trait in your Livewire component.
* Declare the protected property `$mainModelName` in your Livewire component.
* Declare the protected property `$mediaComponentNames` in your Livewire component.
* Appends additional validation rules in the protected property `$rules` of your Livewire component.
* Define protected method `getSeoContent()` to provide the computed seo content meta data.
* Define protected method `getSeoUrl()` to provide the computed seo url meta data.
* Add a line of code inside the `save()` method, calling the `saveSeoMeta()` method.

Please take a look at the example below:

```php
<?php

namespace App\Http\Livewire\Cms\Posts;

use App\Models\Post;
use Cms\Livewire\Concerns\WithSeoMeta;
use Livewire\Component;
use Spatie\MediaLibraryPro\Http\Livewire\Concerns\WithMedia;

abstract class PostForm extends Component
{
    // ... Existing class used traits
 
    // Add these traits in your Livewire form component.
    use WithMedia;
    use WithSeoMeta;

    /**
     * Define the main model's property name which implements
     * the SeoAttachedModel interface here.
     * 
     * This property is required by WithSeoMeta trait.
     *
     * @var string
     */
    protected string $mainModelName = 'post';

    /**
     * Register the media component names.
     *
     * @var string[]
     */
    public array $mediaComponentNames = [];
    
    /**
     * The related post instance.
     *
     * @var Post
     */
    public Post $post;

    /**
     * The validation rules for post model.
     *
     * @var string[]
     */
    protected array $rules = [
        // ... Existing validation rules

        // Append some validation rules for SEO Meta data.
        'seoMeta.seo_title.*'       => 'required|string|min:2|max:60',
        'seoMeta.seo_description.*' => 'required|string|min:2|max:150',
        'seoMeta.open_graph_type.*' => 'required|in:article,website',
    ];
    
    /**
     * Get computed SEO content meta data.
     *
     * @param Post $post
     * @param string $locale
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function getSeoContent(Post $post, string $locale): string
    {
        return strip_tags((string) $post->getAttribute('content'));
    }

    /**
     * Get computed SEO url meta data.
     *
     * @param Post $post
     * @param string $locale
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function getSeoUrl(Post $post, string $locale): string
    {
        return '/' . $post->getAttribute('slug');
    }

    /**
     * Save the post model.
     *
     * @throws \ErrorException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Livewire\Exceptions\PropertyNotFoundException
     *
     * @return mixed
     */
    public function save()
    {
        if (($this->operation !== 'create') && ($this->operation !== 'update')) {
            return redirect()->to(route('cms.posts.index'));
        }

        $this->confirmAuthorization();
        $this->validate();

        $this->post->save();
        
        // You only need to add this single line of code
        // to save the attached SEO Meta data
        $this->saveSeoMeta();

        session()->flash('alertType', 'success');
        session()->flash('alertMessage', $this->getSuccessMessage());

        return redirect()->to(route('cms.posts.index'));
    }
}
```

### Livewire Form Blade Template

You can attach the SEO Meta form inputs in any of your blade template with this single line of code:

```html
@include($seoMetaBlade, ['component' => $this])
```

### API Related Design Patterns

#### Query Builder

```php
<?php

namespace App\QueryBuilders;

final class PostBuilder extends Builder
{
    /**
     * Get a list of allowed relationships that can be used in any include operations.
     *
     * @return string[]
     */
    protected function getAllowedIncludes(): array
    {
        return [
            // Allow include seoMetas relationship
            'seoMetas',
        ];
    }
}
```

#### Resource Collection

```php
<?php

namespace App\Http\Resources;

use Cms\Resources\Concerns\StripResourceElements;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    // Use StripResourceElements trait
    // to remove unnecessary elements from json response
    use StripResourceElements;

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        // Remove `seo_metas` and `seo_meta` elements from json response.
        return $this->stripElementsFromCollection(
            parent::toArray($request),
            ['seo_metas', 'seo_meta']
        );
    }
}
```

#### Json Resource

```php
<?php

namespace App\Http\Resources;

use Cms\Resources\Concerns\StripResourceElements;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    // Use StripResourceElements trait
    // to remove unnecessary elements from json response
    use StripResourceElements;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        // Remove `seo_metas` and `seo_meta` elements from json response.
        return $this->stripElementsFromResource(
            parent::toArray($request),
            ['seo_metas', 'seo_meta']
        );
    }
}
```

#### Retrieve Attached SEO Meta Data From API Call

Retrieve posts collection in paginated format along with some SEO Meta Data attached in the collection.

```bash
curl -X GET \
    -G "http://localhost:8000/api/posts?include=seoMetas&append=seo_url,seo_title,seo_description,seo_image" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

Retrieve a single post collection along with some SEO Meta Data attached in the json document.

```bash
curl -X GET \
    -G "http://localhost:8000/api/posts/1?include=seoMetas&append=seo_url,seo_title,seo_description,seo_image" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

#### Retrieve SEO Meta Data Directly From Original SEO Meta Resource Endpoint

Retrieve SEO Meta Data for a specific page / url.

```bash
curl -X GET \
    -G "http://localhost:8000/api/seo_metas?filter[seo_url]=/test-page&append=seo_image" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

Perform website universal search through SEO Meta resources

```bash
curl -X GET \
    -G "http://localhost:8000/api/seo_metas?search=my-search-keyword&include=media&append=seo_image_small" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

## Testing

### Testing Livewire Create Component

```php
<?php

namespace Tests\Livewire\Cms\Posts;

use App\Models\Admin;
use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class CreatePostTest extends TestCase
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
    }

    /** @test */
    public function it_can_save_the_new_post_record()
    {
        $data = Post::factory()->raw();
        $seoMeta = $this->fakeAttachedSeoMetaData(Post::class);
        
        // Generate computed SEO Meta Data manually
        $seoMeta['en']['seo_url'] = '/en/posts/'.Str::slug($data['name']);
        $seoMeta['en']['seo_content'] = strip_tags($data['content']['en']);
        $seoMeta['id']['seo_url'] = '/id/posts/'.Str::slug($data['name']);
        $seoMeta['id']['seo_content'] = strip_tags($data['content']['id']);

        Livewire::test('cms.posts.create-post')
            ->set('post.name', $data['name'])
            
            // Assign the fake SEO Meta data into your Livewire component
            ->set('seoMeta.seo_title.en', $seoMeta['en']['seo_title'])
            ->set('seoMeta.seo_description.en', $seoMeta['en']['seo_description'])
            ->set('seoMeta.open_graph_type.en', $seoMeta['en']['open_graph_type'])
            
            // Repeat the assignment of SEO Meta data for each supported language
            ->set('seoMeta.seo_title.id', $seoMeta['id']['seo_title'])
            ->set('seoMeta.seo_description.id', $seoMeta['id']['seo_description'])
            ->set('seoMeta.open_graph_type.id', $seoMeta['id']['open_graph_type'])
            
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/posts');

        $this->assertDatabaseHas('posts', $data);
        
        // Assert that the seo meta data have been saved.
        $this->assertDatabaseHas('seo_metas', $seoMeta['en']);
        $this->assertDatabaseHas('seo_metas', $seoMeta['id']);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The new post has been saved.', session('alertMessage'));
    }

    /** @test */
    public function it_can_cancel_creating_new_post_and_go_back_to_index_page()
    {
        $data = Post::factory()->raw();
        $seoMeta = $this->fakeAttachedSeoMetaData(Post::class);
        
        // Generate computed SEO Meta Data manually
        $seoMeta['en']['seo_url'] = '/en/posts/'.Str::slug($data['name']);
        $seoMeta['en']['seo_content'] = strip_tags($data['content']['en']);
        $seoMeta['id']['seo_url'] = '/id/posts/'.Str::slug($data['name']);
        $seoMeta['id']['seo_content'] = strip_tags($data['content']['id']);

        Livewire::test('cms.posts.create-post')
            ->set('post.name', $data['name'])
            
            // Assign the fake SEO Meta data into your Livewire component
            ->set('seoMeta.seo_title.en', $seoMeta['en']['seo_title'])
            ->set('seoMeta.seo_description.en', $seoMeta['en']['seo_description'])
            ->set('seoMeta.open_graph_type.en', $seoMeta['en']['open_graph_type'])
            
            // Repeat the assignment of SEO Meta data for each supported language
            ->set('seoMeta.seo_title.id', $seoMeta['id']['seo_title'])
            ->set('seoMeta.seo_description.id', $seoMeta['id']['seo_description'])
            ->set('seoMeta.open_graph_type.id', $seoMeta['id']['open_graph_type'])
            
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/posts');

        $this->assertDatabaseMissing('posts', $data);
        
        // Assert that the seo meta data didn't get saved.
        $this->assertDatabaseMissing('seo_metas', $seoMeta['en']);
        $this->assertDatabaseMissing('seo_metas', $seoMeta['id']);
    }
}

```

### Testing Livewire Edit Component

```php
<?php

namespace Tests\Livewire\Cms\Posts;

use App\Models\Admin;
use App\Models\SeoMeta;
use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class EditPostTest extends TestCase
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
     * The Post instance to support any test cases.
     *
     * @var Post
     */
    protected Post $post;

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

        $this->post = Post::factory()->create();
        
        // Create fake seo meta for the post
        $seoMeta = $this->fakeAttachedSeoMetaData(Post::class);
        SeoMeta::factory()->create($seoMeta['en']);
        SeoMeta::factory()->create($seoMeta['id']);
    }

    /** @test */
    public function it_can_update_the_existing_post_record()
    {
        $data = Post::factory()->raw();
        $seoMeta = $this->fakeAttachedSeoMetaData(Post::class);
        
        // Generate computed SEO Meta Data manually
        $seoMeta['en']['seo_url'] = '/en/posts/'.Str::slug($data['name']);
        $seoMeta['en']['seo_content'] = strip_tags($data['content']['en']);
        $seoMeta['id']['seo_url'] = '/id/posts/'.Str::slug($data['name']);
        $seoMeta['id']['seo_content'] = strip_tags($data['content']['id']);

        Livewire::test('cms.posts.edit-post', ['post' => $this->post])
            ->set('post.name', $data['name'])
            
            // Assign the fake SEO Meta data into your Livewire component
            ->set('seoMeta.seo_title.en', $seoMeta['en']['seo_title'])
            ->set('seoMeta.seo_description.en', $seoMeta['en']['seo_description'])
            ->set('seoMeta.open_graph_type.en', $seoMeta['en']['open_graph_type'])
            
            // Repeat the assignment of SEO Meta data for each supported language
            ->set('seoMeta.seo_title.id', $seoMeta['id']['seo_title'])
            ->set('seoMeta.seo_description.id', $seoMeta['id']['seo_description'])
            ->set('seoMeta.open_graph_type.id', $seoMeta['id']['open_graph_type'])
            
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/posts');

        $this->assertDatabaseHas('posts', $data);
        
        // Assert that the updated seo meta data have been saved.
        $this->assertDatabaseHas('seo_metas', $seoMeta['en']);
        $this->assertDatabaseHas('seo_metas', $seoMeta['id']);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The post has been updated.', session('alertMessage'));
    }

    /** @test */
    public function it_can_cancel_updating_existing_post_and_go_back_to_index_page()
    {
        $data = Post::factory()->raw();
        $seoMeta = $this->fakeAttachedSeoMetaData(Post::class);
        
        // Generate computed SEO Meta Data manually
        $seoMeta['en']['seo_url'] = '/en/posts/'.Str::slug($data['name']);
        $seoMeta['en']['seo_content'] = strip_tags($data['content']['en']);
        $seoMeta['id']['seo_url'] = '/id/posts/'.Str::slug($data['name']);
        $seoMeta['id']['seo_content'] = strip_tags($data['content']['id']);

        Livewire::test('cms.posts.edit-post', ['post' => $this->post])
            ->set('post.name', $data['name'])
            
            // Assign the fake SEO Meta data into your Livewire component
            ->set('seoMeta.seo_title.en', $seoMeta['en']['seo_title'])
            ->set('seoMeta.seo_description.en', $seoMeta['en']['seo_description'])
            ->set('seoMeta.open_graph_type.en', $seoMeta['en']['open_graph_type'])
            
            // Repeat the assignment of SEO Meta data for each supported language
            ->set('seoMeta.seo_title.id', $seoMeta['id']['seo_title'])
            ->set('seoMeta.seo_description.id', $seoMeta['id']['seo_description'])
            ->set('seoMeta.open_graph_type.id', $seoMeta['id']['open_graph_type'])
            
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/posts');

        $this->assertDatabaseMissing('posts', $data);
        
        // Assert that the updated seo meta data didn't get saved.
        $this->assertDatabaseMissing('seo_metas', $seoMeta['en']);
        $this->assertDatabaseMissing('seo_metas', $seoMeta['id']);
    }
}
```

### Testing Livewire Show Component

```php
<?php

namespace App\Http\Livewire\Cms\Posts;

use App\Models\Admin;
use App\Models\Post;
use App\Models\SeoMeta;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class ShowPostTest extends TestCase
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
     * The Post instance to support any test cases.
     *
     * @var Post
     */
    protected Post $post;
    
    /**
     * The fake SEO Meta data, which being used in these test cases.
     * 
     * @var array 
     */
    protected array $seoMeta = [];

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

        $this->post = Post::factory()->create();
        
        // Create fake seo meta for the post
        $this->seoMeta = $this->fakeAttachedSeoMetaData(Post::class);
        SeoMeta::factory()->create($this->seoMeta['en']);
        SeoMeta::factory()->create($this->seoMeta['id']);
    }

    /** @test */
    public function show_component_is_accessible()
    {
        Livewire::test('cms.posts.show-post', ['post' => $this->post])
        
            // Asserts that the existing seo meta data have been assigned to the Livewire component
            ->assertSet('seoMeta.seo_title.en', $this->seoMeta['en']['seo_title'])
            ->assertSet('seoMeta.seo_description.en', $this->seoMeta['en']['seo_description'])
            ->assertSet('seoMeta.open_graph_type.en', $this->seoMeta['en']['open_graph_type'])
            
            // Repeat the assertions of SEO Meta data for each supported language
            ->assertSet('seoMeta.seo_title.id', $this->seoMeta['id']['seo_title'])
            ->assertSet('seoMeta.seo_description.id', $this->seoMeta['id']['seo_description'])
            ->assertSet('seoMeta.open_graph_type.id', $this->seoMeta['id']['open_graph_type'])
        
            ->assertHasNoErrors();
    }
}
```
