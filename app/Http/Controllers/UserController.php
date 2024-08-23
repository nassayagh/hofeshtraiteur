<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController
{

    public function __construct() {

    }
    public function index(Request $request) {
        return User::query()
            ->where(function ($query) use ($request) {

                $term = $request->get("term","");
                if(!empty($term)) {
                    $query->where("name","like","%$term%");
                    $query->orWhere("email","like","%$term%");
                }

            })
            ->orderBy($request->get("order_by","name"),$request->get("direction","ASC"))
            ->paginate($request->get("per_page",10 ));
    }
    public function store(Request $request) {
        $input = $request->all();
        if($request->filled('id')){
            $user = User::find($input['id']);
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                "email" => [
                    'required',
                    Rule::unique('users')->ignore($user->id), // Assuming $user is the user being updated
                ],
                'password' => 'nullable|string|min:6',
            ]);
            if($validator->fails()) {
                return response()->json(["errors" => ["validation" => $validator->errors()]], 422);
            }
        }else{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                "email" => "required|email|unique:users",
                'password' => 'required|min:6',
            ]);
            if($validator->fails()) {
                return response()->json(["errors" => ["validation" => $validator->errors()]], 422);
            }
        }



        if(!empty($input["id"])){
            $user = User::find($input['id']);
            $user->name = $input['name'];
            $user->email = $input['email'];
            $user->roles = json_encode($request->get("roles",'[]'));
            if($request->has("changePassword") && $input["changePassword"]){
                $user->password = Hash::make($input["password"]);
            }
            $user->save();
            return response()->json($user, 201);
        }else{
            $input['roles'] = json_encode($request->get("roles",'[]'));
            $user = User::create($input);
            return response()->json($user, 201);
        }

    }

    public function destroy(Request $request,$id) {
        User::find($id)->delete();
        return response()->json(null, 204);
    }

}
