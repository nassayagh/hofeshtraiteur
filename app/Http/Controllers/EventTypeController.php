<?php

namespace App\Http\Controllers;

use App\Models\EventType;
use App\Models\Prestation;
use App\Models\PrestationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EventTypeController
{

    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $events = EventType::query();

        if ($request->has("statistics")) {

            $events = $events->whereHas("prestations");
            for($i = 0; $i<4; $i++) {
                $current_year = date('Y');
                $current_year -= $i;
                $events = $events->withCount(["prestations as prestation_$current_year" => fn($q) => $q->whereYear("created_at","=", $current_year)]);
            }

        } else {
            $events = $events->where(function ($query) use ($request) {

                $term = $request->get("term", "");

                if (!empty($term)) {
                    $query->where("name", "like", "%$term%");/*
                    $query->orWhere("description","like","%$term%");*/
                }

            });
        }

        $events = $events->orderBy($request->get("order_by", "id"), $request->get("direction", "ASC"));
        $events = $events->paginate($request->get("per_page", 10));

        return $events;
    }

    public function store(Request $request)
    {
        $input = $request->all();
        if ($request->filled('id')) {
            $EventType = EventType::find($input['id']);
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(["errors" => ["validation" => $validator->errors()]], 422);
            }
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(["errors" => ["validation" => $validator->errors()]], 422);
            }
        }


        if (!empty($input["id"])) {
            $EventType = EventType::find($input['id']);
            $EventType->name = $input['name'];
            /*$EventType->description = $input['description'] ?? "";*/
            $EventType->save();
            return response()->json($EventType, 201);
        } else {
            $EventType = EventType::create($request->all());
            return response()->json($EventType, 201);
        }

    }

    public function destroy(Request $request, $id)
    {
        EventType::find($id)->delete();
        return response()->json(null, 204);
    }

}
