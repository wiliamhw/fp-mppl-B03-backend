<?php

namespace App\Http\Resources;

use Cms\Resources\Concerns\StripResourceElements;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SeoMetaCollection extends ResourceCollection
{
    use StripResourceElements;

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (is_array(parent::toArray($request))) {
            return $this->stripElementsFromResource(parent::toArray($request), ['media']);
        }

        return parent::toArray($request);
    }
}
