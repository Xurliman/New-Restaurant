<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\UserResource;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    public function leaveComment(Request $request)
    {
        $user = $request->user();

        $validation = Validator::make($request->all(), [
            'comment' => 'required'
        ]);

        if ($validation->fails()) {
            return ResponseController::error($validation->errors()->first());
        }

        Comment::create([
            'user_id' => $user->id,
            'comment' => $request->comment
        ]);

        return ResponseController::success();
    }

    public function index()
    {
        $comments = CommentResource::collection(Comment::with("user")->latest()->limit(5)->get());
        return ResponseController::response($comments);

    }

    public function allUserComments($user_id)
    {
        $comments = new UserResource(User::with("comments")->findOrFail($user_id));
        return ResponseController::response($comments);
    }
}
