<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderStatsResource;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends BaseController
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository
    ) {}

    public function store(StoreOrderRequest $request): JsonResponse
    {
      
        try {
            $order = $this->orderRepository->create($request->validated());
            return $this->sendResponse(
                new OrderResource($order),
                'Order created successfully',
                null
            );
        } catch (\Exception $e) {
            return $this->sendError('Error creating order', [], 500);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $status = request()->query('status');
            
            if ($status && !in_array($status, ['pending', 'shipped'])) {
                return $this->sendError('Invalid status. Allowed values: pending, shipped', [], 422);
            }

            $orders = $status 
                ? $this->orderRepository->getByStatus($status)
                : $this->orderRepository->all();
                
            return $this->sendResponse(
                OrderResource::collection($orders),
                $status 
                    ? "Orders filtered by status: {$status}"
                    : 'All orders retrieved successfully',
                $status ? ['status' => $status] : null
            );
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving orders', [], 500);
        }
    }

    public function filterByStatus(string $status): JsonResponse
    {
        try {
            if (!in_array($status, ['pending', 'shipped'])) {
                return $this->sendError('Invalid status. Allowed values: pending, shipped', [], 422);
            }

            $orders = $this->orderRepository->getByStatus($status);
            return $this->sendResponse(
                OrderResource::collection($orders),
                "Orders filtered by status: {$status}",
                ['status' => $status]
            );
        } catch (\Exception $e) {
            return $this->sendError('Error filtering orders', [], 500);
        }
    }

    public function update(UpdateOrderRequest $request, int $id): JsonResponse
    {
        try {
            $order = $this->orderRepository->find($id);
            
            if (!$order) {
                return $this->sendError('Order not found', [], 404);
            }

            $this->orderRepository->update($id, ['status' => $request->status]);
            return $this->sendResponse(
                new OrderResource($order),
                'Order status updated successfully',
                null
            );
        } catch (\Exception $e) {
            return $this->sendError('Error updating order', [], 500);
        }
    }

    public function stats(): JsonResponse
    {
        try {
            $stats = $this->orderRepository->getOrderStats();
            return $this->sendResponse(
                new OrderStatsResource($stats),
                'Order statistics retrieved successfully',
                null
            );
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving order statistics', [], 500);
        }
    }
} 