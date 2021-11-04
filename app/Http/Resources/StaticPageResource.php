<?php

namespace App\Http\Resources;

use Cms\Resources\Concerns\StripResourceElements;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class StaticPageResource extends JsonResource
{
    use StripResourceElements;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (is_array(parent::toArray($request))) {
            return $this->stripElementsFromCollection(parent::toArray($request), ['seo_metas', 'seo_meta']);
        }

        return parent::toArray($request);
    }
}
