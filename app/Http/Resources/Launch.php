<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class Launch extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $date = Carbon::createFromTimestamp(strtotime($this->resource->launch_date_utc));
        return [
            'number'  => $this->resource->flight_number,
            'date'    => $date->format('Y-m-d'),
            'name'    => $this->resource->mission_name,
            'link'    => $this->resource->links->article_link,
            'details' => $this->resource->details,
        ];
    }
}
