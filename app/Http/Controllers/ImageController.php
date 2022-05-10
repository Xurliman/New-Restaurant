<?php

namespace App\Http\Controllers;

use Dotenv\Repository\RepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'images'=>'required'
        ]);
        
        if($validation->fails()){
            return ResponseController::error($validation->errors()->first(), 422);
        }
        
        $images = $request->file("images");
        $data = [];
        foreach ($images as $value) {
                $name = time().'_'.Str::random(10).'.'.$value->getClientOriginalExtension();
                $value->storeAs('public/images', $name);
                $NewName = asset('/storage/images/'.$name);
                $data[] = $NewName;
        }
        return ResponseController::response($data);
    }

    public function destroy($fileName)
    {
        $path = storage_path('app/public/images/'.$fileName);

        if (!$path) {
            return ResponseController::error('Image does not exist');
        }
        File::delete($path);
        return ResponseController::success('Image has been successfully deleted');
    }
}
