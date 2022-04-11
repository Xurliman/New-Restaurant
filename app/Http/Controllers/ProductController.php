<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\ResponseController;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();
        if (!AdminController::check($user)) {
            return ResponseController::error('Permission denied, not admin', 403);
        }

        $validation = Validator::make($request->all(), [
            'category_id'=>'required|exists:categories,id',
            'name'=>'required|min:4|max:30|unique:products,name',
            'description'=>'required|min:4|max:255',
            'price'=>'required|numeric|min:1',
            'images'=> 'required'
        ]);
        if($validation->fails()){
            return ResponseController::error($validation->errors()->first(), 422);
        }
        
        Product::create([
            'category_id'=>$request->category_id,
            'name'=>$request->name,
            'description'=>$request->description,
            'price'=>$request->price,
            'images'=>$request->images
        ]);
        return ResponseController::success('Product has been successfully created');
    }

    public function edit(Request $request, $product_id)
    {
        $user = $request->user();
        if (!AdminController::check($user)) {
            return ResponseController::error('Permission denied, not admin', 403);
        }
        $product = Product::where('id', $product_id)->first();
        if (!$product) {
            return ResponseController::error('Product that has this kind of id, does not exist', 404);
        }
        if (empty($request->all())) {
            return ResponseController::error("At least one field should be given to update");
        }
        $product->update($request->all());
        return ResponseController::success('Product has been successfully edited');
    }

    public function delete(Request $request, $product_id)
    {
        $user = $request->user();
        if (!AdminController::check($user)) {
            return ResponseController::error('Permission denied, not admin', 403);
        }
        if (Product::find($product_id)) {
            ResponseController::error('Product not found', 404);
        }
        Product::destroy($product_id);
        return ResponseController::success('Product has successfully been deleted');
    }

    public function showWithCat()
    {
        $data = [];
        $temp = [];
        $categories = Category::all();
        foreach ($categories as $category) {
            $temp = [
                'category_id'=>$category->id,
                'category_name'=>$category->name,
                'products' => []
            ];
            $products = $category->product;
            foreach ($products as $item) {
                $temp['products'][] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'price' => $item->price,
                    'images' => $item->images
                ];
            }
            $data[] = $temp;
            $temp = [];
        } 
        return ResponseController::response($data);
    }

    public function singleProduct($product_id)
    {
        $product = Product::where('id', $product_id)->first();
        if (!$product) {
            return ResponseController::error('Product does not exist', 404);
        }
        $category = Category::where('id', $product->category_id)
            ->select('id', 'name')
            ->first();
        $oneProduct = [
            'id' => $product->id,
            'category_id' => $category->id,
            'category_name' => $category->name,
            'product_name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'product_name' => $product->name,
            'images' => $product->images
        ];
        return ResponseController::response($oneProduct);
    }

    public function view(){
        $product = Product::all();
        $data = [];
        foreach ($product as $value) {
            $data[] = [
                'id' => $value->id,
                'category_id' => $value->category_id,
                'product_name' => $value->name,
                'description' => $value->description,
                'price' => $value->price,
                'images' => $value->images,
            ];
        }
        return ResponseController::response($data);
    }
}
