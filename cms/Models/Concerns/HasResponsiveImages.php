<?php

namespace Cms\Models\Concerns;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasResponsiveImages
{
    /**
     * Extract all of the responsive image dataset from the given media instance.
     *
     * @param Media  $media
     * @param string $conversionName
     *
     * @return array
     */
    protected function extractResponsiveImageDataset(Media $media, string $conversionName): array
    {
        $srcset = $media->getSrcset($conversionName);
        $dataset = [];

        if ($srcset === '') {
            return $dataset;
        }

        $responsiveImages = explode(', ', $srcset);

        foreach ($responsiveImages as $responsiveImage) {
            [$image, $width] = explode(' ', $responsiveImage);

            $dataset[$width] = $image;
        }

        return $dataset;
    }

    /**
     * Get the responsive image dataset from the given media instance,
     * without its placeholder.
     *
     * @param Media  $media
     * @param string $conversionName
     *
     * @return array
     */
    public function getResponsiveImageDataset(Media $media, string $conversionName): array
    {
        $collection = collect($this->extractResponsiveImageDataset($media, $conversionName));
        $fallback = $collection->get('32w');
        $srcset = $collection->forget('32w')->map(function ($image, $width) {
            return $image . ' ' . $width;
        })->implode(', ');

        return [
            'onload' => 'window.requestAnimationFrame(function(){if(!(size=getBoundingClientRect().width))return;onload=null;sizes=Math.ceil(size/window.innerWidth*100)+\'vw\';});',
            'sizes' => '1px',
            'src' => $fallback,
            'srcset' => $srcset,
        ];
    }
}
