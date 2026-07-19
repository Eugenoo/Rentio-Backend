<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompleteProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $limit = min((int) $request->query('limit', 10), 100);

        // Pobranie użytkowników z paginacją
        $users = User::orderBy('created_at', 'desc')->paginate($limit);

        return $users;
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function create(Request $request)
    {
        //check if email exist and return response if exist if not then go next :)
        $data = $request->validate([
            "name"=>"required",
            "email"=>"required",
            "password"=>"required", //rethinkable
        ]);
        //there must be option to instantly create password after login or autogenerate?
        $data['role'] = 'user';

        $user = User::make($data);

        return response($user, "200")
            ->header('Content-Type', 'text/plain');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id'=>'required',
            "name"=>"nullable",
            "last_name"=>"nullable",
            "phone"=>"nullable",
            "email"=>"nullable",
        ]);

        $user = User::findOrFail($data['id']);

        $user->edit($data);

        return response()->json([
            'user' => $user
        ]);
    }

    public function delete(Request $request)
    {
        $data = $request->validate([
            'id'=>'required',
        ]);
        $user = User::findOrFail($data['id']);

        $user->delete();

        return response()->json([
            'user' => $user
        ]);
    }

    public function complete(CompleteProfileRequest $request)
    {
        $user = auth()->user();

        $user->update([
            ...$request->validated(),
            'profile_completed' => true,
        ]);

        return response()->json([
            'message' => 'Profile completed.',
            'user' => $user
        ]);
    }
}
