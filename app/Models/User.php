<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationship với Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relationship với CartItem
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Relationship với ChatbotMessage
    public function chatbotMessages()
    {
        return $this->hasMany(ChatbotMessage::class);
    }

    // Relationship với ChatbotResponse (created_by admin)
    public function chatbotResponses()
    {
        return $this->hasMany(ChatbotResponse::class, 'created_by');
    }

    // Relationship com Review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Check if user is customer
    public function isCustomer()
    {
        return $this->role === 'customer';
    }
}
