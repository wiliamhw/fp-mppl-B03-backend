<?php

namespace App\Models\Concerns;

use Illuminate\Http\Request;
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
        if (!($image instanceof TemporaryUploadedFile)) {
            return;
        }

        if (isset($imageUrl)) {
            $this->clearMediaCollection($collectionName);
        }

        $this
            ->addMediaFromDisk(
                'livewire-tmp/'.$image->getFilename(), config('livewire.temporary_file_upload.disk')
            )
            ->usingName(sha1((string) time()))
            ->usingFileName(sha1((string) time()))
            ->toMediaCollection($collectionName);
    }

    /**
     * Store media from API
     *
     * @param Request $request
     * @param string $collectionName
     * @param string $fileName
     */
    public function storeMediaFromApi(Request $request, string $collectionName, string $fileName): void
    {
        if (!$request->hasFile($fileName)) {
            return;
        }

        $oldImageExist = ($this->getFirstMedia(self::IMAGE_COLLECTION) != null);
        if ($oldImageExist) {
            $this->clearMediaCollection($collectionName);
        }

        $this
            ->addMediaFromRequest($fileName)
            ->usingName(sha1((string) time()))
            ->usingFileName(sha1((string) time()))
            ->toMediaCollection($collectionName);
    }
}
