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
       'image',
       'description',
       'category_id'
   ];

   // Relationship với Category
   public function category()
   {
       return $this->belongsTo(Category::class);
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
