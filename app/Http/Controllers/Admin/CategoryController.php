<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(15);
        // Bạn cần tạo view: resources/views/admin/categories/index.blade.php
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        // Bạn cần tạo view: resources/views/admin/categories/create.blade.php
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    public function storeAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $category = Category::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Thêm danh mục thành công!',
            'category' => $category
        ]);
    }

    public function edit(Category $category)
    {
        // Bạn cần tạo view: resources/views/admin/categories/edit.blade.php
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy(Request $request, Category $category)
    {
        try {
            //  chỉ xóa nếu không có sản phẩm nào thuộc danh mục
            if ($category->products()->count() > 0) {
                $message = 'Không thể xóa danh mục này vì vẫn còn sản phẩm thuộc về nó.';
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 409); // 409 Conflict
                }
                return redirect()->route('admin.categories.index')->with('error', $message);
            }

            $category->delete();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Xóa danh mục thành công!']);
            }
            return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công!');

        } catch (\Exception $e) {
            $errorMessage = 'Đã xảy ra lỗi khi xóa.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMessage], 500);
            }
            return redirect()->route('admin.categories.index')->with('error', $errorMessage);
        }
    }
}