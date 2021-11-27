<?php

namespace App\Models\Concerns;

use App\Contracts\FileUploadRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait HandleImage
{
    /**
     * Get the large image attribute (mutator).
     *
     * @return string|null
     */
    public function getLargeImageAttribute()
    {
        $image = $this->getFirstMediaUrl($this->getImageMediaCollectionName(), 'large');

        return ($image === '') ? $image : asset($image);
    }

    /**
     * Get the large detail responsive images.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getLargeResponsiveImagesAttribute()
    {
        $image = $this->getFirstMedia($this->getImageMediaCollectionName());

        return $this->getResImage($image, 'large');
    }

    /**
     * Get the medium image attribute (mutator).
     *
     * @return string|null
     */
    public function getMediumImageAttribute()
    {
        $image = $this->getFirstMediaUrl($this->getImageMediaCollectionName(), 'medium');

        return ($image === '') ? $image : asset($image);
    }

    /**
     * Get the Medium detail responsive images.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getMediumResponsiveImagesAttribute()
    {
        $image = $this->getFirstMedia($this->getImageMediaCollectionName());

        return $this->getResImage($image, 'medium');
    }

    /**
     * Get the small image attribute (mutator).
     *
     * @return string|null
     */
    public function getSmallImageAttribute()
    {
        $image = $this->getFirstMediaUrl($this->getImageMediaCollectionName(), 'small');

        return ($image === '') ? $image : asset($image);
    }

    /**
     * Get the small detail responsive images.
     *
     * @return Collection
     */
    public function getSmallResponsiveImagesAttribute()
    {
        $image = $this->getFirstMedia($this->getImageMediaCollectionName());

        return $this->getResImage($image, 'small');
    }

    /**
     * Get the small image attribute (mutator).
     *
     * @return string|null
     */
    public function getExtraSmallImageAttribute()
    {
        $image = $this->getFirstMediaUrl($this->getImageMediaCollectionName(), 'extra_small');

        return ($image === '') ? $image : asset($image);
    }

    /**
     * Get the large detail responsive images.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getExtraSmallResponsiveImagesAttribute(): Collection
    {
        $image = $this->getFirstMedia($this->getImageMediaCollectionName());

        return $this->getResImage($image, 'extra_small');
    }

    /**
     * Get the thumbnail image attribute (mutator).
     *
     * @return string|null
     */
    public function getThumbnailAttribute()
    {
        $image = $this->getFirstMediaUrl($this->getImageMediaCollectionName(), 'thumbnail');

        return ($image === '') ? $image : asset($image);
    }

    /**
     * Get image collections attribute.
     *
     * @return array
     */
    public function getImageCollectionsAttribute()
    {
        $collections = [];

        foreach ($this->getMedia($this->getImageMediaCollectionName()) as $media) {
            $collections[] = [
                'id'        => $media->id,
                'name'      => $media->file_name,
                'original'  => asset($media->getUrl()),
                'large'     => $media->hasGeneratedConversion('large') ? asset($media->getUrl('large')) : '',
                'small'     => $media->hasGeneratedConversion('small') ? asset($media->getUrl('small')) : '',
                'medium'    => $media->hasGeneratedConversion('medium') ? asset($media->getUrl('medium')) : '',
                'size'      => $media->size,
            ];
        }

        return $collections;
    }

    /**
     * Get image collections attribute.
     *
     * @return array
     */
    public function getImageResponsiveCollectionsAttribute()
    {
        $collections = [];

        foreach ($this->getMedia($this->getImageMediaCollectionName()) as $media) {
            $collections[] = [
                'id'        => $media->id,
                'name'      => $media->file_name,
                'large'     => $this->getResImage($media, 'large'),
                'small'     => $this->getResImage($media, 'small'),
                'medium'    => $this->getResImage($media, 'medium'),
                'size'      => $media->size,
            ];
        }

        return $collections;
    }

    /**
     * Save the image which being submitted in the given http request.
     *
     * @param FileUploadRequest $request
     * @param string            $columnName     "image"
     * @param string            $collectionName
     *
     * @throws \ErrorException
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     *
     * @return $this
     */
    public function saveImage(FileUploadRequest $request, string $columnName = 'image', ?string $collectionName = null): self
    {
        $collectionName = $collectionName ?: $this->getImageMediaCollectionName();
        $file = $request->file($columnName);

        if ($file instanceof UploadedFile) {
            $this->handleUploadedMedia(Arr::wrap($file), $collectionName);
        }

        $this->append(['large_image', 'small_image']);

        return $this;
    }

    /**
     * Get the image media collection name.
     *
     * @return string
     */
    public function getImageMediaCollectionName(): string
    {
        return self::IMAGE_COLLECTION;
    }

    /**
     * Get responsive images.
     *
     * @param mixed  $image
     * @param string $size
     *
     * @return Collection
     */
    public function getResImage($image, $size)
    {
        if ($image) {
            $items = $image->getResponsiveImageUrls($size);
            $result = collect();
            foreach ($items as $item) {
                $key = \Str::of($item)->after($size);
                $key = \Str::of($key)->ltrim('_');
                $key = \Str::of($key)->replaceMatches('/.(?:png|jpg|jpeg)/', '');
                $keys = \Str::of($key)->explode('_');
                if (count($keys) > 1) {
                    $result->add([
                        'width'  => $keys[0],
                        'height' => $keys[1],
                        'url'    => $item,
                    ]);
                }
            }

            return $result;
        }

        return collect();
    }

    public function registerSingleCollection(array $sizes): void
    {
        foreach ($sizes as $name => $size) {
            /** @phpstan-ignore-next-line */
            $this->addMediaConversion($name)
                ->keepOriginalImageFormat()
                ->quality(config('media-library.quality', 95))
                ->optimize()
                ->withResponsiveImages()
                ->height($size['h'])
                ->width($size['w'])
                ->sharpen(10)
                ->performOnCollections(self::IMAGE_COLLECTION)
                ->queued();
        }
    }

    /**
     * Register conversions by given sizes.
     *
     * @param array $sizes
     *
     * @return void
     */
    public function registerMultilingualConversions(array $sizes): void
    {
        foreach ($sizes as $name => $size) {
            /** @phpstan-ignore-next-line */
            $this->addMediaConversion($name)
                ->keepOriginalImageFormat()
                ->quality(config('media-library.quality', 95))
                ->optimize()
                ->withResponsiveImages()
                ->width($size['w'])
                ->height($size['h'])
                ->sharpen(10)
                ->performOnCollections(self::IMAGE_COLLECTION.'-en', self::IMAGE_COLLECTION.'-id')
                ->queued();
        }
    }
}
