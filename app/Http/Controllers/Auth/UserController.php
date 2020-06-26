<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function current(Request $request)
    {
        return response()->json($request->user());
    }
}
