<?php

namespace App\Models\Concerns;

use Livewire\TemporaryUploadedFile;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait ConvertImage
{
    use HandleImage;
    use HandleUploadedMedia;
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $imageCollections = $this->getAllImageCollections();
        foreach ($imageCollections as $imageCollection) {
            $this->addMediaCollection($imageCollection)
                ->singleFile()
                ->acceptsMimeTypes([
                    'image/jpg',
                    'image/jpeg',
                    'image/png',
                ]);
        }
    }

    private array $sizes = [
        'extra_small' => [
            'w' => 150,
            'h' => 100,
        ],
        'small' => [
            'w' => 253,
            'h' => 142,
        ],
        'medium' => [
            'w' => 541,
            'h' => 336,
        ],
        'large' => [
            'w' => 896,
            'h' => 505,
        ],
    ];

    /**
     * Get image collection.
     *
     * @return array
     */
    abstract protected function getAllImageCollections(): array;

    /**
     * Register media conversions.
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function registerMediaConversions(Media $media = null): void
    {
        foreach ($this->sizes as $name => $size) {
            /** @phpstan-ignore-next-line */
            $this->addMediaConversion($name)
                ->keepOriginalImageFormat()
                ->quality(config('media-library.quality', 95))
                ->optimize()
                ->withResponsiveImages()
                ->width($size['w'])
                ->height($size['h'])
                ->sharpen(10)
                ->performOnCollections(...$this->getAllImageCollections())
                ->queued();
        }
    }

    /**
     * Store media
     *
     * @param mixed $image
     * @param string $collectionName
     * @param string|null $imageUrl
     */
    public function storeMedia(mixed $image, string $collectionName, string|null $imageUrl): void
    {
        if (isset($imageUrl)) {
            $this->clearMediaCollection($collectionName);
        }

        if (!($image instanceof TemporaryUploadedFile)) {
            return;
        }

        $this
            ->addMediaFromDisk(
                'livewire-tmp/'.$image->getFilename(), config('livewire.temporary_file_upload.disk')
            )
            ->usingName(sha1(time()))
            ->usingFileName(sha1(time()))
            ->toMediaCollection($collectionName);
    }
}
