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
            $orders = $status 
                ? $this->orderRepository->getByStatus($status)
                : $this->orderRepository->all();
                
            return $this->sendResponse(
                OrderResource::collection($orders),
                'Orders retrieved successfully',
                null
            );
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving orders', [], 500);
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
            $status = request()->query('status');
            $stats = $this->orderRepository->getOrderStats($status);
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