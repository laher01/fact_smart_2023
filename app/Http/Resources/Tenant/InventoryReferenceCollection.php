<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Configuration;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InventoryReferenceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($row, $key) {

        
            return [
                'id' => $row->id,
                'code' => $row->code,
                'description' => $row->description,
            ];
        });
    }



}
