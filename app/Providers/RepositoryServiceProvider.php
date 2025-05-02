<?php

namespace App\Providers;

use App\Repositories\CustomerRepository;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\OrderRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    public function boot(): void
    {
        //
    }
} 