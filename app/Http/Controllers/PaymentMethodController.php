<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentMethodController
{

    public function __construct() {

    }
    public function index(Request $request) {
        $methods = PaymentMethod::query();

        if($request->has("statistics")) {
            $methods = $methods->withSum("payments", "amount")->withCount("payments");
        } else {
            $methods = $methods->where(function ($query) use ($request) {

                $term = $request->get("term","");
                if(!empty($term)) {
                    $query->where("name","like","%$term%");/*
                    $query->orWhere("description","like","%$term%");*/
                }

            });
        }


        $methods = $methods->orderBy($request->get("order_by","name"),$request->get("direction","ASC"))
            ->paginate($request->get("per_page",10 ));
        return $methods;
    }
    public function store(Request $request) {
        $input = $request->all();
        if($request->filled('id')){
            $PaymentMethod = PaymentMethod::find($input['id']);
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if($validator->fails()) {
                return response()->json(["errors" => ["validation" => $validator->errors()]], 422);
            }
        }else{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if($validator->fails()) {
                return response()->json(["errors" => ["validation" => $validator->errors()]], 422);
            }
        }



        if(!empty($input["id"])){
            $PaymentMethod = PaymentMethod::find($input['id']);
            $PaymentMethod->name = $input['name'];
            /*$PaymentMethod->description = $input['description'] ?? "";*/
            $PaymentMethod->save();
            return response()->json($PaymentMethod, 201);
        }else{
            $PaymentMethod = PaymentMethod::create($request->all());
            return response()->json($PaymentMethod, 201);
        }

    }

    public function destroy(Request $request,$id) {
        PaymentMethod::find($id)->delete();
        return response()->json(null, 204);
    }

}
