<?php

namespace Cms\Testing;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibraryPro\Models\TemporaryUpload;

class FakeTemporaryUpload
{
    /**
     * The current temporary uploads session.
     *
     * @var TemporaryUpload
     */
    protected TemporaryUpload $uploadSession;

    /**
     * FakeTemporaryUpload constructor.
     */
    public function __construct()
    {
        Storage::fake(config('media-library.disk_name'));

        $this->uploadSession = TemporaryUpload::create([
            'session_id' => session()->getId(),
        ]);
    }

    /**
     * Add images to the temporary uploads.
     *
     * @param int $quantity
     *
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     *
     * @return $this
     */
    public function addImage(int $quantity = 1): self
    {
        while ($quantity >= 1) {
            $this->uploadSession->addMediaFromDisk('image.jpg', 'dummies')
                ->preservingOriginal()
                ->toMediaCollection();

            $quantity--;
        }

        return $this;
    }

    /**
     * Create a new instance of FakeTemporaryUpload.
     *
     * @param string|null $mediaType
     * @param int         $quantity
     *
     * @return FakeTemporaryUpload
     */
    public static function create(?string $mediaType = null, int $quantity = 1): self
    {
        $newInstance = new self();
        $addMethod = 'add'.ucfirst(strtolower((string) $mediaType));

        if (($mediaType !== null) && method_exists($newInstance, $addMethod)) {
            $newInstance->$addMethod($quantity);
        }

        return $newInstance;
    }

    /**
     * Extract necessary information from the given media instance.
     *
     * @param Collection $collection
     * @param Media      $media
     */
    protected function extractMediaInformation(Collection $collection, Media $media): void
    {
        $data = [
            'name'       => $media->getAttribute('file_name'),
            'fileName'   => $media->getAttribute('file_name'),
            'uuid'       => $media->getAttribute('uuid'),
            'previewUrl' => asset('storage/dummies/image.jpg'),
            'order'      => $media->getAttribute('order_column'),
            'size'       => $media->getAttribute('size'),
            'mime_type'  => $media->getAttribute('mime_type'),
            'extension'  => $media->getExtensionAttribute(),
        ];

        $collection->put($media->getAttribute('uuid'), $data);
    }

    /**
     * Get the temporary uploads collection.
     *
     * @return array
     */
    public function getCollection(): array
    {
        $collection = collect();
        foreach ($this->uploadSession->media as $media) {
            if ($media instanceof Media) {
                $this->extractMediaInformation($collection, $media);
            }
        }

        return $collection->toArray();
    }
}
