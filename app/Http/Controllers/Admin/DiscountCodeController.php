<?php

namespace App\Http\Controllers\Admin;

use App\Models\DiscountCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiscountCodeController extends Controller
{
    /**
     * Hiển thị danh sách mã giảm giá
     */
    public function index()
    {
        $codes = DiscountCode::paginate(10);
        return view('admin.discount-codes.index', ['codes' => $codes]);
    }

    /**
     * Hiển thị form tạo mã giảm giá
     */
    public function create()
    {
        return view('admin.discount-codes.create');
    }

    /**
     * Lưu mã giảm giá mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discount_codes',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed_amount',
            'value' => 'required|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'boolean'
        ]);

        DiscountCode::create($validated);

        return redirect()->route('admin.discount-codes.index')
            ->with('success', '✅ Mã giảm giá đã được tạo thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa mã giảm giá
     */
    public function edit(DiscountCode $code)
    {
        return view('admin.discount-codes.edit', ['code' => $code]);
    }

    /**
     * Cập nhật mã giảm giá
     */
    public function update(Request $request, DiscountCode $code)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discount_codes,code,' . $code->id,
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed_amount',
            'value' => 'required|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'boolean'
        ]);

        $code->update($validated);

        return redirect()->route('admin.discount-codes.index')
            ->with('success', '✅ Mã giảm giá đã được cập nhật thành công!');
    }

    /**
     * Xóa mã giảm giá
     */
    public function destroy(DiscountCode $code)
    {
        $code->delete();
        return redirect()->route('admin.discount-codes.index')
            ->with('success', '✅ Mã giảm giá đã được xóa thành công!');
    }

    /**
     * Vô hiệu hóa/kích hoạt mã giảm giá
     */
    public function toggle(DiscountCode $code)
    {
        $code->update(['is_active' => !$code->is_active]);
        
        $status = $code->is_active ? 'kích hoạt' : 'vô hiệu hóa';
        return redirect()->route('admin.discount-codes.index')
            ->with('success', "✅ Mã giảm giá đã được {$status} thành công!");
    }
}
