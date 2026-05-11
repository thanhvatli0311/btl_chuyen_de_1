@extends('layout')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4>➕ Thêm Sản Phẩm Mới</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">📝 Tên Sản Phẩm</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">💰 Giá (VNĐ)</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   name="price" value="{{ old('price') }}" step="0.01" required>
                            @error('price')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📦 Số Lượng</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                   name="quantity" value="{{ old('quantity') }}" required>
                            @error('quantity')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📁 Danh Mục</label>
                            <div class="input-group">
                                <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" id="category_id_select" required>
                                    <option value="">-- Chọn Danh Mục --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-outline-success" type="button" data-bs-toggle="modal" data-bs-target="#addCategoryModal" title="Thêm danh mục mới">➕</button>
                                <button class="btn btn-outline-danger" type="button" id="deleteSelectedCategoryBtn" title="Xóa danh mục đang chọn">🗑️</button>
                            </div>
                            @error('category_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">🏢 Hãng Sản Xuất</label>
                            <div class="input-group">
                                <select class="form-select @error('brand_id') is-invalid @enderror" name="brand_id" id="brand_id_select">
                                    <option value="">-- Chọn Hãng --</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-outline-success" type="button" data-bs-toggle="modal" data-bs-target="#addBrandModal" title="Thêm hãng mới">➕</button><button class="btn btn-outline-danger" type="button" id="deleteSelectedBrandBtn" title="Xóa hãng đang chọn">🗑️</button>
                            </div>
                            @error('brand_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📄 Mô Tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      name="description" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    ⭐ Sản Phẩm Hot (Sẽ hiển thị trước tiên)
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📸 Ảnh Sản Phẩm</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   name="image" accept=".jpeg,.jpg,.png,.gif,.webp,image/webp" required>
                            @error('image')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Tối đa 2MB, định dạng: jpeg, png, jpg, gif, webp</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">✅ Thêm Sản Phẩm</button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">❌ Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm Hãng Mới -->
<div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addBrandModalLabel">Thêm Hãng Sản Xuất Mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addBrandForm" onsubmit="return false;">
            <div class="alert alert-danger d-none" id="brand_form_errors"></div>
            <div class="mb-3">
                <label for="new_brand_name" class="form-label">Tên Hãng</label>
                <input type="text" class="form-control" id="new_brand_name" name="name" required>
                <div class="invalid-feedback" id="brand_name_error"></div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-primary" id="saveNewBrandBtn">Lưu Hãng</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Thêm Danh Mục Mới -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCategoryModalLabel">Thêm Danh Mục Mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addCategoryForm" onsubmit="return false;">
            <div class="mb-3">
                <label for="new_category_name" class="form-label">Tên Danh Mục</label>
                <input type="text" class="form-control" id="new_category_name" name="name" required>
                <div class="invalid-feedback" id="category_name_error"></div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-primary" id="saveNewCategoryBtn">Lưu Danh Mục</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const saveNewBrandBtn = document.getElementById('saveNewBrandBtn');
    const addBrandForm = document.getElementById('addBrandForm');
    const newBrandNameInput = document.getElementById('new_brand_name');
    const brandNameError = document.getElementById('brand_name_error');
    const brandSelect = document.getElementById('brand_id_select');
    const addBrandModalEl = document.getElementById('addBrandModal');
    const addBrandModal = new bootstrap.Modal(addBrandModalEl);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Category Modal elements
    const saveNewCategoryBtn = document.getElementById('saveNewCategoryBtn');
    const addCategoryForm = document.getElementById('addCategoryForm');
    const newCategoryNameInput = document.getElementById('new_category_name');
    const categoryNameError = document.getElementById('category_name_error');
    const categorySelect = document.getElementById('category_id_select');
    const addCategoryModal = new bootstrap.Modal(document.getElementById('addCategoryModal'));

    saveNewBrandBtn.addEventListener('click', function () {
        const formData = new FormData(addBrandForm);
        newBrandNameInput.classList.remove('is-invalid');

        fetch('{{ route("admin.brands.store.ajax") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const newOption = new Option(data.brand.name, data.brand.id, true, true);
                brandSelect.appendChild(newOption);
                addBrandModal.hide();
                addBrandForm.reset();
            } else if (data.errors && data.errors.name) {
                newBrandNameInput.classList.add('is-invalid');
                brandNameError.textContent = data.errors.name[0];
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Logic for deleting a brand
    const deleteBrandBtn = document.getElementById('deleteSelectedBrandBtn');
    deleteBrandBtn.addEventListener('click', function() {
        const selectedOption = brandSelect.options[brandSelect.selectedIndex];
        const brandId = selectedOption.value;

        if (!brandId) {
            alert('Vui lòng chọn một hãng để xóa.');
            return;
        }

        if (confirm(`Bạn có chắc chắn muốn xóa hãng "${selectedOption.text}" không? Các sản phẩm thuộc hãng này sẽ không bị xóa.`)) {
            // The URL is constructed manually. Ensure your route prefix is '/admin'
            fetch(`/admin/brands/${brandId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    selectedOption.remove();
                } else {
                    alert('Lỗi: ' + (data.message || 'Không thể xóa hãng.'));
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });

    // Logic for adding a category
    saveNewCategoryBtn.addEventListener('click', function () {
        const formData = new FormData(addCategoryForm);
        newCategoryNameInput.classList.remove('is-invalid');

        fetch('{{ route("admin.categories.store.ajax") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const newOption = new Option(data.category.name, data.category.id, true, true);
                categorySelect.appendChild(newOption);
                addCategoryModal.hide();
                addCategoryForm.reset();
            } else if (data.errors && data.errors.name) {
                newCategoryNameInput.classList.add('is-invalid');
                categoryNameError.textContent = data.errors.name[0];
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Logic for deleting a category
    const deleteCategoryBtn = document.getElementById('deleteSelectedCategoryBtn');
    deleteCategoryBtn.addEventListener('click', function() {
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const categoryId = selectedOption.value;

        if (!categoryId) {
            alert('Vui lòng chọn một danh mục để xóa.');
            return;
        }

        if (confirm(`Bạn có chắc chắn muốn xóa danh mục "${selectedOption.text}" không? Hành động này chỉ thành công nếu không có sản phẩm nào thuộc danh mục này.`)) {
            fetch(`/admin/categories/${categoryId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    selectedOption.remove();
                    alert(data.message);
                } else {
                    alert('Lỗi: ' + (data.message || 'Không thể xóa danh mục.'));
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
});
</script>
@endsection
