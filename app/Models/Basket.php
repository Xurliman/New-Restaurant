<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Basket extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'room_id',
        'people',
        'date',
        'price',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:00',
        'updated_at' => 'datetime:Y-m-d H:00',
        'deleted_at' => 'datetime:Y-m-d H:00',
    ];

    public function Order(){
        return $this->HasMany(Order::class, 'basket_id');
    }

    public function scopeWaiting($query){
        return $query->where('status', 'waiting');
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function room(){
        return $this->belongsTo(Room::class);
    }
}
