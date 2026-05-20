<?php

// tests/Feature/CartControllerTest.php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Category;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        // Chuẩn bị dữ liệu test
        
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $category = Category::create([
            'name' => 'Test Category ' . time(),
        ]);

        $this->product = Product::create([
            'name' => 'Test Product',
            'price' => 1000000,
            'quantity' => 10,
            'image' => 'default.png',
            'description' => 'Test description',
            'category_id' => $category->id,
        ]);
    }

    /**
     * Test case: User thêm sản phẩm vào giỏ hàng thành công
     */
    public function test_add_product_to_cart_success(): void
    {
        // Thực thi: User authenticated thêm sản phẩm
        $response = $this->actingAs($this->user)
            ->post("/add-cart/{$this->product->id}");

        // Khẳng định: Redirect về cart, cart item được tạo
        $response->assertRedirect('/cart');
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);
    }

    /**
     * Test case: User không thể thêm sản phẩm khi chưa đăng nhập
     */
    public function test_add_product_requires_authentication(): void
    {
        // Thực thi: User KHÔNG authenticated
        $response = $this->post("/add-cart/{$this->product->id}");

        // Khẳng định: Redirect về login
        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('cart_items', [
            'product_id' => $this->product->id
        ]);
    }

    /**
     * Test case: User không thể thêm sản phẩm hết hàng
     */
    public function test_cannot_add_out_of_stock_product(): void
    {
        // Chuẩn bị: Sản phẩm hết hàng
        $this->product->update(['quantity' => 0]);

        // Thực thi: User authenticated cố gắng thêm
        $response = $this->actingAs($this->user)
            ->post("/add-cart/{$this->product->id}");

        // Khẳng định: Lỗi, không tạo cart item
        $response->assertRedirect('/cart');
        $response->assertSessionHas('error', '❌ Sản phẩm này đã hết hàng!');
        $this->assertDatabaseMissing('cart_items', [
            'product_id' => $this->product->id
        ]);
    }

    /**
     * Test case: User cập nhật số lượng sản phẩm trong giỏ
     */
    public function test_update_cart_item_quantity(): void
    {
        // Chuẩn bị: Cart item đã tồn tại
        CartItem::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2
        ]);

        // Thực thi: User cập nhật số lượng thành 5
        $response = $this->actingAs($this->user)
            ->post("/cart/update/{$this->product->id}", ['quantity' => 5]);

        // Khẳng định: Database cập nhật thành 5
        $response->assertRedirect('/cart');
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 5
        ]);
    }

    /**
     * Test case: User xóa sản phẩm khỏi giỏ hàng
     */
    public function test_remove_product_from_cart(): void
    {
        // Chuẩn bị: Cart item đã tồn tại
        CartItem::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2
        ]);

        // Thực thi: User xóa sản phẩm
        $response = $this->actingAs($this->user)
            ->post("/cart/remove/{$this->product->id}");

        // Khẳng định: Cart item bị xóa
        $response->assertRedirect('/cart');
        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id
        ]);
    }
}