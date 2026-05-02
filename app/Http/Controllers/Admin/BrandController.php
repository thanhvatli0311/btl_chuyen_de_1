<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->paginate(15);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('logo')) {
            $imageName = time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('images/brands'), $imageName);
            $validated['logo'] = $imageName;
        }

        Brand::create($validated);

        return redirect()->route('admin.brands.index')->with('success', 'Thêm hãng thành công!');
    }

    /**
     * Store a newly created brand via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $brand = Brand::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Thêm hãng thành công!',
            'brand'   => $brand
        ]);
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('logo')) {
            // Xóa logo cũ
            if ($brand->logo && file_exists(public_path('images/brands/' . $brand->logo))) {
                @unlink(public_path('images/brands/' . $brand->logo));
            }
            $imageName = time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('images/brands'), $imageName);
            $validated['logo'] = $imageName;
        }

        $brand->update($validated);

        return redirect()->route('admin.brands.index')->with('success', 'Cập nhật hãng thành công!');
    }

    public function destroy(Request $request, Brand $brand)
    {
        try {
            // Xóa logo
            if ($brand->logo && file_exists(public_path('images/brands/' . $brand->logo))) {
                @unlink(public_path('images/brands/' . $brand->logo));
            }

            // Các sản phẩm thuộc hãng này sẽ có brand_id = null do đã thiết lập ở migration
            $brand->delete();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Xóa hãng thành công!']);
            }
            return redirect()->route('admin.brands.index')->with('success', 'Xóa hãng thành công!');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Đã xảy ra lỗi khi xóa.'], 500);
            }
            return redirect()->route('admin.brands.index')->with('error', 'Đã xảy ra lỗi khi xóa.');
        }
    }
}