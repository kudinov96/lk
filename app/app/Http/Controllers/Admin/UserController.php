<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use TCG\Voyager\Http\Controllers\VoyagerUserController;

class UserController extends VoyagerUserController
{
    public function index(Request $request)
    {
        $users = User::query()->get();

        return response()->view("admin.user.index", compact(
            "users",
        ));
    }
}
