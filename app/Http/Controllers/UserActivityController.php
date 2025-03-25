<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserActivity;

class UserActivityController extends Controller
{
    public static function store($ip, $user_id = null)
    {
        UserActivity::create([
            'ip' => $ip,
            'user_id' => $user_id,
        ]);
    }
}
