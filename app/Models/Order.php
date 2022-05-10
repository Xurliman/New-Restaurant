<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'basket_id',
        'product_id',
        'count'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function basket(){
        return $this->hasMany(Basket::class);
    }

    public function product(){
        return $this->hasMany(Product::class);
    }
}
