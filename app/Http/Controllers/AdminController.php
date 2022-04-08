<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public static function check($user)
    {
        if ($user->role != 'admin') {
            return false;
        }
        return true;

    }
}
