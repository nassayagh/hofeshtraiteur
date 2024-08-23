<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function Pest\Laravel\json;

class CustomerController
{

    const _RELATIONS = ['prestations','demands'];
    public function __construct() {

    }
    public function index(Request $request) {
        return Customer::query()
            ->where(function ($query) use ($request) {

                $term = $request->get("search","");
                $status = $request->get("status",null);
                $date = $request->get("date",null);
                if($date != null) {
                    $date1 = Carbon::parse($date[0])->startOfDay();
                    $date2 = Carbon::parse($date[1])->endOfDay();

                    $query->whereBetween('created_at', [$date1, $date2]);
                }
                if($status != null) {
                    $query->where("is_customer",$status);
                }
                if(!empty($term)) {
                    $query->where("firstname","like","%$term%");
                    $query->orWhere("lastname","like","%$term%");
                    $query->orWhere("phone","like","%$term%");
                    $query->orWhere("email","like","%$term%");
                }

            })->with(self::_RELATIONS)->withCount(["prestations"])
            ->orderBy($request->get("order_by","firstname"),$request->get("direction","asc"))
            ->paginate($request->get("per_page",10 ));
    }
    public function statistics(Request $request) {
        return [
            "customers" => Customer::query()->where("is_customer", 1)->count(),
            "prospects" => Customer::query()->where("is_customer", 0)->count(),
        ];
    }
    public function show(Request $request,$id) {
        return Customer::query()->where('id',$id)->with(self::_RELATIONS)->withSum("payments","amount")->first();
    }
    public function store(Request $request) {
        $input = $request->all();
        if($request->filled('id')){
            $Customer = Customer::find($input['id']);
            $validator = Validator::make($request->all(), [
                'firstname' => 'required',
            ]);
            if($validator->fails()) {
                return response()->json(["errors" => $validator->errors()], 422);
            }
        }else{
            $validator = Validator::make($request->all(), [
                'firstname' => 'required',
            ]);
            if($validator->fails()) {
                return response()->json(["errors" => $validator->errors()], 422);
            }
        }



        if(!empty($input["id"])){
            $Customer = Customer::find($input['id']);
            $Customer->firstname = $input['firstname'];
            $Customer->lastname = $input['lastname'];
            $Customer->phone = $input['phone'];
            $Customer->is_customer = $input['is_customer'] ?? false;
            $Customer->email = $input['email'];
            $Customer->save();
            return response()->json($Customer, 201);
        }else{
            if($request->has("email")){
                $Customer = Customer::where("email",$request->get("email"))->first();
                if($Customer) {
                    return response()->json(["error" => true,"message" => __("Il y a déjà un client avec l'email :email",["email" => $Customer->email ])],200);
                    $Customer->update($request->all());
                    return response()->json($Customer, 201);
                }
            }
            $Customer = Customer::create($request->all());

            return response()->json($Customer, 201);
        }

    }

    public function destroy(Request $request,$id) {
        Customer::find($id)->delete();
        return response()->json(null, 204);
    }

}
