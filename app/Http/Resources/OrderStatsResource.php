<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderStatsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total_orders' => $this['total_orders'],
            'total_revenue_pending' => $this['total_revenue_pending'],
            'total_revenue_shipped' => $this['total_revenue_shipped'],
            'pending_orders' => $this['pending_orders'],
            'shipped_orders' => $this['shipped_orders'],
        ];
    }
} 