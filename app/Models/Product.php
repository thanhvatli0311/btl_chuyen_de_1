<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   protected $fillable = [
       'name',
       'price',
       'quantity',
       'description',
       'category_id',
       'brand_id', // Thêm brand_id
       'image',
       'is_featured'
   ];

   protected $casts = [
       'price' => 'integer',
       'quantity' => 'integer',
       'is_featured' => 'boolean',
   ];

   // Relationship với Category
   public function category()
   {
       return $this->belongsTo(Category::class);
   }

   // Relationship với Brand
   public function brand()
   {
       return $this->belongsTo(Brand::class);
   }

   // Relationship với OrderItem
   public function orderItems()
   {
       return $this->hasMany(OrderItem::class);
   }

   // Relationship với Review
   public function reviews()
   {
       return $this->hasMany(Review::class);
   }
}
