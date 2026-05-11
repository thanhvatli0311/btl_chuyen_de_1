{{-- 
    Reusable Product Filter Component
    Accepts: $brands, $queryInput, $selectedBrands, $priceRange, $sort
--}}
<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-3">🔍 Lọc sản phẩm</h5>
        <form action="{{ route('products.search') }}" method="GET" class="row g-3 align-items-end">
            {{-- Giữ lại query tìm kiếm nếu có --}}
            @if(isset($queryInput) && !empty($queryInput))
                <input type="hidden" name="query" value="{{ $queryInput }}">
            @endif

            {{-- Lọc theo Hãng --}}
            <div class="col-lg-4 col-md-6">
                <label for="brands" class="form-label">Hãng</label>
                <select id="brand_id" name="brand_id" class="form-select">
                    <option value="">Tất cả hãng</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ ($selectedBrandId ?? 0) == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Lọc theo Khoảng giá --}}
            <div class="col-lg-3 col-md-6">
                <label for="price_range" class="form-label">Khoảng giá</label>
                <select id="price_range" name="price_range" class="form-select">
                    <option value="" {{ ($priceRange ?? '') == '' ? 'selected' : '' }}>Tất cả</option>
                    <option value="0-10000000" {{ ($priceRange ?? '') == '0-10000000' ? 'selected' : '' }}>Dưới 10,000,000₫</option>
                    <option value="10000000-50000000" {{ ($priceRange ?? '') == '10000000-50000000' ? 'selected' : '' }}>10,000,000₫ - 50,000,000₫</option>
                    <option value="50000000-100000000" {{ ($priceRange ?? '') == '50000000-100000000' ? 'selected' : '' }}>50,000,000₫ - 100,000,000₫</option>
                    <option value="100000000-500000000" {{ ($priceRange ?? '') == '100000000-500000000' ? 'selected' : '' }}>100,000,000₫ - 500,000,000₫</option>
                    <option value="500000000-9999999999" {{ ($priceRange ?? '') == '500000000-9999999999' ? 'selected' : '' }}>Trên 500,000,000₫</option>
                </select>
            </div>

            {{-- Sắp xếp --}}
            <div class="col-lg-3 col-md-6">
                <label for="sort" class="form-label">Sắp xếp</label>
                <select id="sort" name="sort" class="form-select">
                    <option value="latest" {{ ($sort ?? 'latest') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="price_asc" {{ ($sort ?? '') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                    <option value="price_desc" {{ ($sort ?? '') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                </select>
            </div>

            {{-- Nút Lọc --}}
            <div class="col-lg-2 col-md-6">
                <button type="submit" class="btn btn-primary w-100">Lọc</button>
            </div>
        </form>
    </div>
</div>