<?php

namespace App\Http\Resources;

use Cms\Resources\Concerns\StripResourceElements;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    use StripResourceElements;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return $this->stripElementsFromResource((array) parent::toArray($request), [
            'created_at',
            'updated_at',
            'media',
        ]);
    }
}
