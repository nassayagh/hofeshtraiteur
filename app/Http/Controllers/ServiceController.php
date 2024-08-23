<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use App\Models\Prestation;
use App\Models\PrestationService;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ServiceController
{

    public function __construct() {

    }
    public function index(Request $request) {
        return Service::query()
            ->where(function ($query) use ($request) {

                $term = $request->get("term","");
                if(!empty($term)) {
                    $query->where("name","like","%$term%");
                    $query->orWhere("description","like","%$term%");
                }
                $prestation = $request->get("prestation", null);
                if(!$prestation) {
                    $serviceIds = PrestationService::where("prestation_id",$prestation)->get()->pluck("service_id");
                    $query->whereNotIn("id",$serviceIds);
                }


            })
            /*->where(function ($query) use ($request) {


                $prestation = $request->get("prestation", null);
                if(!$prestation) {
                    $serviceIds = PrestationService::where("prestation_id",$prestation)->get()->pluck("service_id");
                    $query->whereNotIn("id",$serviceIds);
                }


            })*/
            ->orderBy($request->get("order_by","name"),$request->get("direction","ASC"))
            ->paginate($request->get("per_page",10 ));
    }
    public function store(Request $request) {
        $input = $request->all();
        if($request->filled('id')){
            $Service = Service::find($input['id']);
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
            $Service = Service::find($input['id']);
            $Service->name = $input['name'];
            $Service->price = $input['price'];
            $Service->description = $input['description'] ?? "";
            $Service->save();
            return response()->json($Service, 201);
        }else{
            $Service = Service::create($request->all());
            return response()->json($Service, 201);
        }

    }

    public function destroy(Request $request,$id) {
        Service::find($id)->delete();
        return response()->json(null, 204);
    }


}
