# Working with Media Uploads

## Table of contents

* [Introduction To Spatie Media Library](#introduction-to-spatie-media-library)
* [Eloquent Model Setup](#eloquent-model-setup)
* [Livewire Form Component Setup](#livewire-form-component-setup)
* [Blade Template Implementation](#blade-template-implementation)
* [API Implementation](#api-implementation)
* [Testing](#testing)

## Introduction To Spatie Media Library

[Spatie Laravel MediaLibrary](https://github.com/spatie/laravel-medialibrary) is one of the most popular Laravel packages, with more than four million downloads. The package is very helpful to associate any kind of file with any Eloquent Model. It is recommended for you to use this package to make application development easier with its simple and fluent API.

You can find out more and learn all about this package by reading [the extensive documentation](https://spatie.be/docs/laravel-medialibrary/v9/introduction). If you are a visual learner, you might want to check [this video course](https://spatie.be/videos/discovering-laravel-media-library).

Some of the snippets and documentation in this file are also taken from the official documentation.

## Eloquent Model Setup

### Preparing Your Model

To associate media with a model, the model must implement the following interface and trait:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class YourModel extends Model implements HasMedia
{
    use InteractsWithMedia;
}
```

### Defining Media Collections

A media collection can be more than just a name to group files. By defining a media collection in your model you can add certain behaviour collections.

To get started with media collections add a function called `registerMediaCollections` to your prepared model. Inside that function you can use `addMediaCollection` to start a media collection.

```php
    /**
     * Register media collections.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('post_image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->registerMediaConversions(function () {
                $this->addMediaConversion('post_image_large')
                    ->crop('crop-center', 1440, 900)
                    ->quality(config('cms.media_quality'))
                    ->sharpen(5)
                    ->optimize()
                    ->withResponsiveImages();

                $this->addMediaConversion('post_image_small')
                    ->crop('crop-center', 240, 150)
                    ->quality(config('cms.media_quality'))
                    ->sharpen(10)
                    ->optimize()
                    ->withResponsiveImages();
            });
    }
```

### Create Accessor To Retrieve Media Conversions

If you're new to the model accessor concept, you can read more about it at [the official documentation](https://laravel.com/docs/8.x/eloquent-mutators#defining-an-accessor).

```php
    /**
     * Get the computed post_image Attribute.
     *
     * @return string
     */
    public function getPostImageAttribute(): string
    {
        $image = $this->getFirstMediaUrl('post_image', 'post_image_large');

        return ($image === '') ? '' : asset($image);
    }

    /**
     * Get the computed post_image_small Attribute.
     *
     * @return string
     */
    public function getPostImageSmallAttribute(): string
    {
        $image = $this->getFirstMediaUrl('post_image', 'post_image_small');

        return ($image === '') ? '' : asset($image);
    }
```

### Create Accessor To Retrieve Media Conversion With Responsive Images

It is recommended to use the `Cms\Models\Concerns\HasResponsiveImages` trait in your model, which provides easy way to retrieve any responsive image dataset and placeholder.

```php
namespace App\Models;

use Cms\Models\Concerns\HasResponsiveImages;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class YourModel extends Model implements HasMedia
{
    use HasResponsiveImages;
    use InteractsWithMedia;
}
```

Then you need to create some accessor which would provide the responsive image data.

```php
    /**
     * Get the computed post_image Attribute.
     * This attributes contains array of responsive image dataset.
     *
     * @return array
     */
    public function getPostImageAttribute(): array
    {
        return $this->getResponsiveImageDataset(
            $this->getFirstMedia('post_image'),
            'post_image_large'
        );
    }

    /**
     * Get the computed post_image_placeholder Attribute.
     * This attributes contains the image binary content in base64 format.
     *
     * @return array
     */
    public function getPostImagePlaceholderAttribute(): array
    {
        return $this->getResponsiveImagePlaceholder(
            $this->getFirstMedia('post_image'),
            'post_image_large'
        );
    }
```

## Livewire Form Component Setup

Since we are using Laravel Livewire in our CMS application, it is required for us to use the [Spatie Media Library Pro](https://medialibrary.pro/) package.

There is [an extensive documentation](https://spatie.be/docs/laravel-medialibrary/v9/handling-uploads-with-media-library-pro/handling-uploads-with-livewire) about how to handle media uploads with Laravel Media Library Pro and Laravel Livewire.

Here are the steps that you need to implement in your Livewire Form component, so it can manage any media uploads.

* Use the `Spatie\MediaLibraryPro\Http\Livewire\Concerns\WithMedia` trait in your Livewire Form class.
* Declare the public `$mediaComponentNames` property.
* Declare the public media component property. You can choose any preferred name for this property.
* Append validation rules for the uploaded media (optional).
* Add some logic to save the uploaded media inside the `save()` method.
* Call garbage collection to clear any uploaded media from temporary storage after saving the uploaded media.

```php
<?php

namespace App\Http\Livewire\Cms\Posts;

use App\Models\Post;
use Livewire\Component;
use Spatie\MediaLibraryPro\Http\Livewire\Concerns\WithMedia;

abstract class PostForm extends Component
{
    use WithMedia;

    /**
     * Register the media component names.
     *
     * @var string[]
     */
    public array $mediaComponentNames = [
        'postImage',
    ];

    /**
     * The validation rules for post model.
     *
     * @var string[]
     */
    protected array $rules = [
        // ... Existing validation rules
        
        // You can skip appending validation rule if the media is optional (nullable).
        'postImage' => 'required',
    ];

    /**
     * The related post instance.
     *
     * @var Post
     */
    public Post $post;

    /**
     * The Media Library Pro's Request instance.
     *
     * @var mixed
     */
    public $postImage;

    /**
     * Save the post model.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
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
        
        // Saving the uploaded media
        $this->post->addFromMediaLibraryRequest($this->postImage)
            ->toMediaCollection('post_image');

        // Garbage collection
        $this->clearMedia();

        session()->flash('alertType', 'success');
        session()->flash('alertMessage', $this->getSuccessMessage());

        return redirect()->to(route('cms.posts.index'));
    }
}
```

## Blade Template Implementation

The blade template implementation has been well documented in [the official documentation](https://spatie.be/docs/laravel-medialibrary/v9/handling-uploads-with-media-library-pro/handling-uploads-with-livewire).

## API Implementation

#### Query Builder Modification

Register the `media` relationship into the allowed includes list.

```php
    /**
     * Get a list of allowed relationships that can be used in any include operations.
     *
     * @return string[]
     */
    protected function getAllowedIncludes(): array
    {
        return [
            'media',
        ];
    }
```

#### Resource Collection

Strip the `media` elements from json response document to remove any unnecessary information.

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
        // Remove `media` elements from json response.
        return $this->stripElementsFromCollection(parent::toArray($request), ['media']);
    }
}
```

#### Json Resource

Strip the `media` elements from json response document to remove any unnecessary information.

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
        // Remove `media` elements from json response.
        return $this->stripElementsFromResource(parent::toArray($request), ['media']);
    }
}
```

#### Retrieve Media Conversion From API Call

Retrieve posts collection in paginated format along with some specific media conversions attached in the collection.

```bash
curl -X GET \
    -G "http://localhost:8000/api/posts?include=media&append=post_image,post_image_small,post_image_placeholder" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

Retrieve a single post resource along with some specific media conversions attached in the json document.

```bash
curl -X GET \
    -G "http://localhost:8000/api/posts/1?include=media&append=post_image,post_image_small,post_image_placeholder" \
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
use Cms\Testing\FakeTemporaryUpload;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
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
    public function it_can_save_the_new_post_record_with_uploaded_media()
    {
        $data = Post::factory()->raw();
        $media = FakeTemporaryUpload::create('image')->getCollection();

        Livewire::test('cms.posts.create-post')
            ->set('post.name', $data['name'])
            ->set('postImage', $media)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/posts');

        $this->assertDatabaseHas('posts', $data);

        $post = Post::orderBy('id', 'desc')->first();
        self::assertInstanceOf(Post::class, $post);

        $media = $post->getFirstMedia('post_image');
        self::assertInstanceOf(Media::class, $media);

        $path = str_replace(dirname(\Storage::disk('public')->path('test')), '', $media->getPath());
        Storage::disk(config('media-library.disk_name'))->assertExists($path);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The new post has been saved.', session('alertMessage'));
    }
}
```

### Testing Livewire Edit Component

```php
<?php

namespace Tests\Livewire\Cms\Posts;

use App\Models\Admin;
use App\Models\Post;
use Cms\Testing\FakeTemporaryUpload;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
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
    }

    /** @test */
    public function it_can_update_the_existing_post_record_with_uploaded_media()
    {
        $data = Post::factory()->raw();
        $media = FakeTemporaryUpload::create('image')->getCollection();

        Livewire::test('cms.posts.edit-post', ['post' => $this->post])
            ->set('post.name', $data['name'])
            ->set('postImage', $media)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/posts');

        $this->assertDatabaseHas('posts', $data);

        $this->post->refresh();

        $media = $this->post->getFirstMedia('post_image');
        self::assertInstanceOf(Media::class, $media);

        $path = str_replace(dirname(\Storage::disk('public')->path('test')), '', $media->getPath());
        Storage::disk(config('media-library.disk_name'))->assertExists($path);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The post has been updated.', session('alertMessage'));
    }
}
```
