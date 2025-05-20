<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
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
            "password"=>"required",      //rethinkable
        ]);
        //there must be option to instantly create password after login or autogenerate?
        $user = User::make($data);

        return response("User Created", "200")
            ->header('Content-Type', 'text/plain');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id'=>'required',
            "name"=>"nullable",
            "email"=>"nullable",
        ]);

        $user = User::findOrFail($data['id']);
        $user->edit($data);
        return response("User Updated", "200")
            ->header('Content-Type', 'text/plain');
    }

    public function delete(Request $request)
    {
        $data = $request->validate([
            'id'=>'required',
        ]);
        $user = User::findOrFail($data['id']);
        return response("User: ".$user->name." email: ".$user->email." Deleted", "200")
            ->header("Content-Type", 'text/plain');
    }
}
