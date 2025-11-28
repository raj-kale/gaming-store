<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // numeric auto-increment id (Laravel default) — no $incrementing / keyType override needed
    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'game_id',
        'admin_id',
        'type',
        'status',
        'price',
        'rented_at',
        'due_at',
        'returned_at',
        'sold_at',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rented_at' => 'datetime',
        'due_at' => 'datetime',
        'returned_at' => 'datetime',
        'sold_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeRentals($query)
    {
        return $query->where('type', 'rental');
    }

    public function scopeSales($query)
    {
        return $query->where('type', 'sale');
    }
}
