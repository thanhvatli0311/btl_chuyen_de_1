<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    /**
     * Determine if user can review an order
     * User must:
     * 1. Own the order
     * 2. Order must be completed
     */
    public function review(User $user, Order $order): bool
    {
        return $user->id === $order->user_id && $order->status === 'completed';
    }

    /**
     * Determine if user can view order details
     */
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }

    /**
     * Determine if user can update order
     */
    public function update(User $user, Order $order): bool
    {
        return $user->id === $order->user_id && $order->status === 'pending';
    }

    /**
     * Determine if user can cancel order
     */
    public function cancel(User $user, Order $order): bool
    {
        return $user->id === $order->user_id && in_array($order->status, ['pending', 'processing']);
    }
}
