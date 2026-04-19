<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; 

class User extends Authenticatable // Make sure it extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes; 

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'phone',
        'address',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'deleted_at' => 'datetime',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is client
     */
    public function isClient()
    {
        return $this->role === 'client';
    }

    /**
     * Get the cart for this user
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function archivedEmail()
    {
        return $this->hasOne(ArchivedEmail::class);
    }

    /**
     * Get the payments for the user
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}