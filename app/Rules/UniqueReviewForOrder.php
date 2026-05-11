<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Review;

class UniqueReviewForOrder implements ValidationRule
{
    private $orderId;
    private $productId;

    public function __construct($orderId, $productId = null)
    {
        $this->orderId = $orderId;
        $this->productId = $productId;
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
        // Verificar se já existe uma avaliação para este produto nesta ordem
        $reviewExists = Review::where('order_id', $this->orderId)
            ->where('product_id', $value)
            ->exists();

        if ($reviewExists) {
            $fail('Você já avaliou este produto nesta ordem.');
        }
    }
}
