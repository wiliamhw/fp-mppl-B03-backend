<?php

namespace App\Http\Livewire\Concerns;

use Illuminate\Support\Facades\File;

trait HasMedia
{
    public function clearMedia(mixed $image): void
    {
        if (!isset($image)) {
            return;
        }
        try {
            File::delete(config('livewire.temporary_file_upload.disk')
                .'/storage/livewire-tmp/'.$image->getFilename()
            );
        } catch (\Exception $e) {
            \Log::warning($e->getMessage());
        }
    }
}
