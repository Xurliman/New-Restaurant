<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;

class CategoryController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();
        if (!AdminController::check($user)) {
            return ResponseController::error('Permission denied, not admin', 403);
        }
        $validation = Validator::make($request->all(), [
            'name'=>'required|min:4|unique:categories,name'
        ]);
        if($validation->fails()){
            return ResponseController::error($validation->errors()->first(), 422);
        }

        Category::create([
            'name'=>$request->name
        ]);
        return ResponseController::success("Category has been successfully created", 201);
    }

    public function edit(Request $request, $category_id)
    {
        $user = $request->user();
        if (!AdminController::check($user)) {
            return ResponseController::error('Permission denied, not admin', 403);
        }
        $category = Category::where('id', $category_id)->first();
        if (!$category) {
            return ResponseController::error('Category that has this id dos not exist', 404);
        }
        $category->update([
                'name' => trim($request->name) ?? $category->name
            ]);
        return ResponseController::success('Category has been successfully edited', 200);
    }

    public function delete(Request $request, $cat_id)
    {
        $user = $request->user();
        if (!AdminController::check($user)) {
            return ResponseController::error('Permission denied, not admin', 403);
        }
        if (!Category::find($cat_id)) {
            return ResponseController::error('Category not found', 404);
        }
        Category::destroy($cat_id);
        return ResponseController::success('Category has been successfully deleted', 200);
    }

    public function view()
    {
        if (empty(Category::all())) {
            return ResponseController::error('Category list is empty');
        }
        $cats = Category::select('id', 'name as category_name')->get()->toArray();
        return ResponseController::response($cats);
    }
}
