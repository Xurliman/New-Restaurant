<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public static function create($image, $to = "products"){
        $name = time().'_'.Str::random(10).'.'.$image->getClientOriginalExtension();
        $image->storeAs('public/images/'.$to, $name);
        $NewName = asset('/storage/images/'.$to.'/'.$name);

        return $NewName;
    }

    public static function deleteFile($fileName, $from = 'products')
    {
        $path = storage_path('app/public/images/'.$from.'/'.$fileName);
        
        return File::delete($path);
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'images'=>'required|image'
        ]);

        if($validation->fails()){
            return ResponseController::error($validation->errors()->first(), 422);
        }

        $images = $request->images;
        $data = [];
        if (is_array($images)) {
            foreach ($images as $value) {
                $name = time().'_'.Str::random(10).'.'.$value->getClientOriginalExtension();
                $value->storeAs('public/images/', $name);
                $NewName = asset('/storage/images/'.$name);
                $data[] = [
                    $NewName
                ];
            }
        }else {
            $name = time().'_'.Str::random(10).'.'.$images->getClientOriginalExtension();
            $images->storeAs('public/images', $name);
            $NewName = asset('/storage/images/'.$name);
                $data[] = [
                    $NewName
                ];
        }
        return ResponseController::response($data);
    }

    public function destroy($name)
    {

    }
}
