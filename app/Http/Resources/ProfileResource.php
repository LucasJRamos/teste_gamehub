<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user' => UserResource::make($this->resource),
            'portfolio_items' => PortfolioItemResource::collection(
                $this->whenLoaded('portfolioItems')
            ),
        ];
    }
}
