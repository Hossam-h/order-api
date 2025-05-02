<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    public function find(int $id): ?Order
    {
        return Order::find($id);
    }

    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Order::where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return Order::destroy($id);
    }

    public function all(): Collection
    {
        return Order::with('customer')->get();
    }

    public function getByStatus(string $status): Collection
    {
        return Order::with('customer')
            ->where('status', $status)
            ->get();
    }

    public function getOrderStats(): array
    {
        
        $query = Order::query();
        
        $stats = $query->selectRaw('
        COUNT(*) as total_orders,
        
        SUM(CASE WHEN status = "pending" THEN price ELSE 0 END) as pending_orders_totals,
        SUM(CASE WHEN status = "shipped" THEN price ELSE 0 END) as shipped_orders_totals,
        
        SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_orders,
        SUM(CASE WHEN status = "shipped" THEN 1 ELSE 0 END) as shipped_orders
        ')->first();
        
        return [
            'total_orders' => $stats->total_orders ?? 0,
            'total_revenue_pending' => $stats->pending_orders_totals ?? 0,
            'total_revenue_shipped' => $stats->shipped_orders_totals ?? 0,
            'pending_orders' => $stats->pending_orders ?? 0,
            'shipped_orders' => $stats->shipped_orders ?? 0,
        ];
        

    }
} 