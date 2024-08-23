<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use App\Models\Prestation;
use App\Models\Hall;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HallController
{

    public function __construct() {

    }
    public function index(Request $request) {
        $halls =  Hall::query();
        if($request->has("statistics")) {
            $halls = $halls ->withCount(["prestations as prestations_coming" => fn($q) => $q->where("status",">=",Prestation::VALIDATED)->whereYear("event_date",">=", Carbon::now())]);
            $halls = $halls ->withCount(["prestations as prestations_past" => fn($q) => $q->where("status",">=",Prestation::VALIDATED)->whereYear("event_date","<", Carbon::now())]);
        } else {
            $halls = $halls->where(function ($query) use ($request) {

                $term = $request->get("term","");
                if(!empty($term)) {
                    $query->where("name","like","%$term%");
                    $query->orWhere("manager_name","like","%$term%");
                    $query->orWhere("manager_phone","like","%$term%");
                    $query->orWhere("comment","like","%$term%");
                }

            });
        }

           $halls = $halls ->orderBy($request->get("order_by","name"),$request->get("direction","asc"))
            ->paginate($request->get("per_page",10 ));
        return $halls;
    }
    public function store(Request $request) {
        $input = $request->all();
        if($request->filled('id')){
            $Hall = Hall::find($input['id']);
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
            $Hall = Hall::find($input['id']);
            $Hall->update($request->all());
            return response()->json($Hall, 201);
        }else{
            $Hall = Hall::create($request->all());
            return response()->json($Hall, 201);
        }

    }

    public function destroy(Request $request,$id) {
        Hall::find($id)->delete();
        return response()->json(null, 204);
    }


}
