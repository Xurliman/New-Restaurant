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
        $validation = Validator::make($request->all(), [
            'category_id'=>'required',
            'name'=>'required|min:4|max:30|unique:products,name',
            'description'=>'required|min:4|max:255',
            'price'=>'required|numeric|min:1',
            'images'=>'required|image',
        ]);

        if($validation->fails()){
            return ResponseController::error($validation->errors()->first(), 422);
        }

        $images = $request->images;
        $path = ImageController::create($images);
        
        Product::create([
            'category_id'=>$request->category_id,
            'name'=>$request->name,
            'description'=>$request->description,
            'price'=>$request->price,
            'images'=>$path

        ]);

        return ResponseController::success('Product has been successfully created');
    }

    public function edit(Request $request, $product_id)
    {

        $product = Product::where('id', $product_id)->first();

        if (!$product) {
            return ResponseController::error('Product that has this kind of id, does not exist', 404);
        }

        if (empty($request->all())) {
            return ResponseController::error("At least one field should be given to update");
        }

        $product->update($request->all());
        
        // if ($request->images) {
        //     $images = $request->images ?? $request->image;
        //     $fileName = $product->images;
        //     ImageController::deleteFile($fileName, 'products');
        //     $path = ImageController::create($images);
        //     $product->update([
        //         'images' => $images
        //     ]);
        // }
        
        return ResponseController::success('Product has been successfully edited');
    }

    public function delete($product_id)
    {
        if (Product::find($product_id)) {
            ResponseController::error('Product not found', 404);
        }

        Product::destroy($product_id);
        return ResponseController::success('Product has successfully been deleted');
    }

    public function showWithCat()
    {
        $data = [];
        $productWithCat = collect(DB::table('products')
            ->select('products.id', 'products.category_id', 'categories.name as category_name', 'products.name', 'products.description', 'products.price', 'products.images')
            ->join('categories', 'categories.id', 'products.category_id')
            ->get());
        $data = $productWithCat->groupBy('category_name');
        
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
}
