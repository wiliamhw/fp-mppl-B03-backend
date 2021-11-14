<?php

namespace App\Http\Resources;

use Cms\Resources\Concerns\StripResourceElements;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WebinarCollection extends ResourceCollection
{
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
        return $this->stripElementsFromCollection((array) parent::toArray($request), [
            'zoom_id',
            'description',
            'max_participants',
        ]);
    }
}
