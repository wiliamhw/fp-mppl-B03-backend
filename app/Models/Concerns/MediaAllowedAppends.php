<?php

namespace App\Models\Concerns;

trait MediaAllowedAppends
{
    public function getMediaAllowedAppends(): array
    {
        return [
            'extra_small_image',
            'small_image',
            'medium_image',
            'large_image',
            'extra_small_responsive_images',
            'small_responsive_images',
            'medium_responsive_images',
            'large_responsive_images',
        ];
    }
}
