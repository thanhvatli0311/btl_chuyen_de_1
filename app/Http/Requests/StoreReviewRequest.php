<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ProductInOrder;
use App\Rules\UniqueReviewForOrder;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization é feita no controller via Policy
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $orderId = $this->route('order')?->id;

        return [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
                new ProductInOrder($orderId),
                new UniqueReviewForOrder($orderId),
            ],
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Vui lòng chọn sản phẩm.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
            'rating.required' => 'Vui lòng chọn mức đánh giá.',
            'rating.integer' => 'Mức đánh giá phải là một số nguyên.',
            'rating.min' => 'Mức đánh giá phải từ 1 đến 5 sao.',
            'rating.max' => 'Mức đánh giá phải từ 1 đến 5 sao.',
            'comment.string' => 'Bình luận phải là văn bản.',
            'comment.max' => 'Bình luận không được vượt quá 1000 ký tự.',
        ];
    }
}
