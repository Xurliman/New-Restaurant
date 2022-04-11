<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use Illuminate\Http\Request;

class BasketController extends Controller
{
    public function index(Request $request){
        $baskets = Basket::select('id', 'user_id', 'room_id', 'people', 'date', 'price', 'status')->waiting()->get();
        $final = [];
        $temp = [];
        foreach ($baskets as $basket) {
            $orders = $basket->order;
            $temp = [
                'id'=> $basket->id,
                'user'=> [
                    'id'=> $basket->user_id,
                    'name'=> $basket->user->name
                ],
                'room'=> [
                    'id'=> $basket->room->id,
                    'number'=> $basket->room->number,
                    'capacity'=> $basket->room->capacity
                ],
                'people'=> $basket->people,
                'date'=> $basket->date,
                'price'=> $basket->price,
                'status'=> $basket->status,
                'orders'=> []
            ];
            foreach ($orders as $order) {
                $temp['orders'][] = [
                    'id'=> $order->id,
                    'product_id'=> $order->product_id,
                    'product_name'=> $order->product->name ?? null,
                    'count'=> $order->count,
                ];
            }
            $final[] = $temp;
            $temp = [];
        }
        return $final;
    }
}
