<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\OrderItem;

class ProductInOrder implements ValidationRule
{
    private $orderId;

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Verificar se o produto existe na ordem
        $exists = OrderItem::where('order_id', $this->orderId)
            ->where('product_id', $value)
            ->exists();

        if (!$exists) {
            $fail('O produto selecionado não está nesta ordem.');
        }
    }
}
