<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserImposterController extends Controller
{
    public function store()
    {
        return response()->json([
            'success' => true,
            'user_id' => User::factory()->create()->id,
        ], 201);
    }
}
