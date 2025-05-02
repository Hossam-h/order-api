<?php

namespace App\Repositories\Interfaces;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface
{
    public function find(int $id): ?Order;
    public function create(array $data): Order;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function all(): Collection;
    public function getByStatus(string $status): Collection;
    public function getOrderStats(): array;
} 