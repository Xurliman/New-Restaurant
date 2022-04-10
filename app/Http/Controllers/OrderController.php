<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Order;
use App\Models\Basket;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;
use Faker\Provider\Base;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class OrderController extends Controller
{
    public function create(Request $request){
        $validation = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'basket_id' => 'required',
            'room_id' => 'required|exists:rooms,id',
            'people' => 'numeric',
            'price' => 'required',
        ]);
        if ($validation->fails()) {
            return ResponseController::error($validation->errors()->first());
        }
        $products = $request->products;
        $basket = Basket::create([
            'user_id'=> $request->user_id,
            'room_id'=> $request->room_id,
            'people'=> $request->people,
            'date'=> $request->date,
            'price'=> $request->price,
        ]);
        foreach ($products as $product) {
            Order::create([
                'user_id'=> $request->user_id,
                'basket_id'=> $basket->id,
                'product_id'=> $product['product_id'],
                'count'=> $product['count']
            ]);
        }
        return ResponseController::success('Successfully created');
    }

    public function cancelBasket($basket_id){
        if (!Basket::find($basket_id)) {
            return ResponseController::error('Basket not found', 404);
        }
        Basket::destroy($basket_id);
        return ResponseController::success('Basket otmenit etildi');
    }

    public function completedHistory()
    {
        $completedBaskets = DB::table('baskets')
            ->whereNotNull('deleted_at')
            ->get();
        if (!$completedBaskets) {
            return ResponseController::error('There are no completed baskets', 404);
        }
        return ResponseController::response($completedBaskets);
    }

    public function completeBasket($basket_id)
    {
        $basket = Basket::find($basket_id);
        if (!$basket) {
            return ResponseController::error('Basket not found', 404);
        }
        $basket->update([
            'is_active' => true
        ]);
        return ResponseController::success('Basket completed');
    }

    public function allBasketsOfUser($user_id)
    {
        $baskets = Basket::where('user_id', $user_id)->withTrashed()->get()->groupBy('user_id');
        if (!$baskets) {
            return ResponseController::error("Basket not found", 404);
        }
        return ResponseController::response($baskets);
    }
    
    public function singleBasket($basket_id)
    {
        $basket = Basket::find($basket_id);
        if (!$basket) {
            return ResponseController::error('Basket not found', 404);
        }
        return ResponseController::response($basket);
    }
    
    public function singleOrder($order_id)
    {
        $order = Order::find($order_id);
        if (!$order) {
            return ResponseController::error('Order not found', 404);
        }
        return ResponseController::response($order);
    }
    
    public function viewAllBaskets()
    {
        $basket = Basket::all();

        return ResponseController::response($basket);
    }
    
    public function viewOrders()
    {
        $orders = Order::all();
        return $orders;
        $orders = DB::table('orders')
        ->select('users.id as user_id', 'orders.count', 'orders.product_id', 'products.name')
        ->join('users', 'orders.id', 'orders.user_id')
        ->join('products', 'products.id', 'orders.product_id')
        ->get();
        
        return ResponseController::response($orders->groupBy('user_id'));
    }
    
    public function viewRooms()
    {
        $room = Room::all();
        return ResponseController::response($room);
    }
    
    public function makeRoom(Request $request)
    {
        $user = $request->user();
        if (!AdminController::check($user)) {
            return ResponseController::error('Permission denied, not admin', 403);
        }
        $validation = Validator::make($request->all(), [
            'capacity' => 'required|numeric',
            'number' => 'required'
        ]);
        if ($validation->fails()) {
            return ResponseController::error($validation->errors()->first(), 422);
        }

        Room::create([
            'capacity' => $request->capacity,
            'number' => $request->number
        ]);
        return ResponseController::success('Room has been successfully added');
    }
    
    public function editRoom(Request $request, $room_id) 
    {
        $user = $request->user();
        if (!AdminController::check($user)) {
            return ResponseController::error('Permission denied, not admin', 403);
        }
        $room = Room::find($room_id);
        if (!$room) {
            return ResponseController::error('Room that has this id does not exist', 404);
        }

        $room->update([$request->all()]);
        return ResponseController::success('Room has been successfully edited');
    }

    public function deleteRoom(Request $request, $room_id)
    {
        $user = $request->user();
        if (!AdminController::check($user)) {
            return ResponseController::error('Permission denied, not admin', 403);
        }
        if (!Room::find($room_id)) {
            return ResponseController::error('Room not found', 404);
        }
        
        Room::destroy($room_id);
        return ResponseController::success("Room has been successfully deleted");
    }   
}
