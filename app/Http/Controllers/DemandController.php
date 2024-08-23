<?php

namespace App\Http\Controllers;

use App\DemandTrait;
use App\Models\Customer;
use App\Models\Demand;
use App\Models\EventType;
use App\Models\Prestation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DemandController
{

    use DemandTrait;
    public function __construct() {

    }
    public function index(Request $request) {

        /*foreach (Demand::all() as $demand){
            if($demand->event_type){
                $eventtype = EventType::query()->where("name",$demand->event_type)->first();
                if($eventtype){
                    $demand->event_type_id = $eventtype->id;
                    $demand->save();
                }else{
                    $eventtype = EventType::create(["name" => $demand->event_type]);
                    if($eventtype){
                        $demand->event_type_id = $eventtype->id;
                        $demand->save();
                    }
                }
            }
        }*/
        $date = $request->get("date",null);
        if($date != null) {
            $date1 = Carbon::parse($date[0])->startOfDay()->format('Y-m-d');
            $date2 = Carbon::parse($date[1])->endOfDay()->format('Y-m-d');
            //return [$date1,$date2];
            //$query->whereBetween('demand_date', [$date1, $date2]);
        }
        return Demand::query()
            ->where("status", "<",Demand::VALIDATED)
            ->where(function ($query) use ($request) {

                $term = $request->get("search","");
                $event_type = $request->get("event_type_id",null);
                $status = $request->get("status",null);
                $date = $request->get("date",null);
                $source = $request->get("source",null);
                if($date != null) {
                    $date1 = Carbon::parse($date[0])->startOfDay();
                    $date2 = Carbon::parse($date[1])->endOfDay();
                   $query->whereBetween('event_date', [$date1, $date2]);
                }
                if($event_type != null) {
                   $query->whereIn('event_type_id', $event_type);
                }
                if($status !== null) {
                    if($status == -1){
                        $query->where('status', $status);
                    }else{
                        $query->where('status',">=", 0);
                    }

                }
                if($source != null) {
                   $query->where('source', $source);
                }
                if(!empty($term)) {

                    $query->where("service","like","%$term%");
                    $query->orWhereHas('customer',function ($query) use ($term) {

                        $query->where("firstname","like","%$term%");
                        $query->orWhere("lastname","like","%$term%");
                        $query->orWhere("phone","like","%$term%");
                        $query->orWhere("email","like","%$term%");
                    });

                }

            })
            ->with('customer','prestation.eventtype',"hall","user","eventtype")
            ->orderBy($request->get("order_by","id"),$request->get("direction","desc"))
            ->paginate($request->get("per_page",10 ));
    }
    public function stats(Request $request)
    {


        $prestions = Demand::select("demands.*");

        if ($request->has("statistics")) {

            $prestions = $prestions->where("status", ">=", Demand::CREATED);
            $prestions = $prestions->whereDoesntHave("prestation");

        }


        $prestions = $prestions->with(['customer','prestation',"hall", "user","eventtype"]);


        $prestions = $prestions->orderBy($request->get("order_by", "id"), $request->get("direction", "desc"));


        $count = $prestions->count();
        $prestions = $prestions->take(10)->get();

        return [
            "data" => $prestions,
            "total" => $count,
        ];
    }
    public function statistics(Request $request) {
        return [
          "total" => Demand::query()->count(),
          "validated" => Demand::query()->where("status", ">=", Demand::VALIDATED)->count(),
          "unvalidated" => Demand::query()->where("status", "=", Demand::CREATED)->count(),
          "cancelled" => Demand::query()->where("status", "=", Demand::CANCELLED)->count(),
        ];
    }

    public function show(Request $request,$id) {
        return Demand::query()->findOrFail($id)->load('customer.demands.eventtype','customer.prestations.demand.eventtype','customer.prestations.eventtype','prestation.demand',"hall","user","eventtype");
    }
    public function addComment(Request $request,$id) {
        $demand =  Demand::query()->findOrFail($id)->load(["customer.demands","user","hall"]);
        $demand->commentaire = $request->get("comment");
        $demand->save();
        $demand->load('customer.demands','customer.prestations.demand','prestation.demand',"user","hall","eventtype");
        return $demand;
    }
    public function cancelDemand(Request $request,$id) {
        $demand =  Demand::query()->findOrFail($id)->load(["customer.demands"]);
        $demand->status = Demand::CANCELLED;
        $demand->save();
        $demand->load('customer.demands','customer.prestations.demand','prestation.demand',"user","hall","eventtype");
        return $demand;
    }
    public function validateDemand(Request $request,$id) {
        $demand =  Demand::query()->findOrFail($id)->load(["customer.demands"]);
        $demand->status = Demand::VALIDATED;
        $demand->save();


        $prestation = new Prestation();
        $prestation->comment = $request->get('comment');
        $prestation->hall_id = $request->get('hall');
        $prestation->status = Prestation::VALIDATED;
        $prestation->validated_date = Carbon::now();
        $prestation->event_type = $demand->event_type;
        $prestation->event_type_id = $demand->event_type_id;
        //$prestation->commentaire = $demand->comment;
        $prestation->event_location = $demand->event_location;
        $prestation->reception_start_time = $demand->reception_start_time;
        $prestation->reception_period = $demand->reception_period;
        $prestation->number_people = $demand->number_people;
        $prestation->event_date = $demand->event_date;
        $prestation->demand_date = $demand->demand_date;
        $prestation->demand_id = $demand->id;
        $prestation->customer_id = $demand->customer_id;
        $prestation->user_id = $demand->user_id;
        $prestation->source = $demand->source;
        $prestation->save();

        $customer = $demand->customer;
        $customer->is_customer = 1;
        $customer->save();

        $demand->load('customer.demands.eventtype','customer.prestations.demand','customer.prestations.eventtype','prestation.demand','prestation.hall',"hall","user");

        return $demand;
    }
    public function store(Request $request) {
        $input = $request->all();
        if($request->filled('id')){
            $Demand = Demand::find($input['id']);
            if($request->has("customer")) {
                $validator = Validator::make($request->all(), [
                    'customer.firstname' => 'required',
                    "customer.email" => [
                        'required',
                        //Rule::unique('demands')->ignore($Demand->id), // Assuming $Demand is the Demand being updated
                    ],
                ]);
                if($validator->fails()) {
                    return response()->json(["errors" => $validator->errors()], 422);
                }
            }else{
                $validator = Validator::make($request->all(), [
                    'firstname' => 'required',
                    "email" => [
                        'required',
                        //Rule::unique('demands')->ignore($Demand->id), // Assuming $Demand is the Demand being updated
                    ],
                ]);
                if($validator->fails()) {
                    return response()->json(["errors" => $validator->errors()], 422);
                }
            }


        }else{

            if($request->has("customer")) {
                $validator = Validator::make($request->all(), [
                    'customer.firstname' => 'required',
                    "customer.email" => "required",
                ]);
                if($validator->fails()) {
                    return response()->json(["errors" => $validator->errors()], 422);
                }

                $cus = $request->get("customer");
                if(empty($cus["id"])){
                    $Customer = Customer::where("email",$cus["email"])->first();
                    if($Customer) {
                        return response()->json(["error" => true,"message" => __("Il y a déjà un client avec l'email :email",["email" => $Customer->email ])],200);
                    }
                }


            }else{
                $validator = Validator::make($request->all(), [
                    'firstname' => 'required',
                    "email" => "required",
                ]);
                if($validator->fails()) {
                    return response()->json(["errors" => $validator->errors()], 422);
                }
            }


        }



        $customer = $this->storeCustomer($request);
        $event = trim($request->get('event_type', "Evenement"));
        $eventType =  EventType::query()->where("name", $event)->first();
        if(!$eventType) {
            $eventType = EventType::create(["name" => $event]);
        }

        if(!empty($input["id"])){
            $demand = Demand::find($input['id']);
            $demand->customer_id = $customer->id;
            $demand->service = $request->get('service');
            $demand->event_type = $event;
            $demand->event_type_id = $request->get("event_type_id",$eventType->id ?? null);
            $demand->reception_start_time = $request->get('reception_start_time');
            $demand->reception_period = $request->get('reception_period');
            $demand->event_location = $request->get('event_location');
            $demand->number_people = $request->get('number_people');
            $demand->hall_id = $request->get('hall_id');
            $demand->comment = $request->get('comment');
            $demand->commentaire = $request->get('commentaire');
            $demand->event_date = $request->get('event_date');
            $demand->demand_date = $request->get('demand_date', Carbon::now());


            $demand->save();
            $prestation = $demand->prestation;
            if($prestation) {
                $prestation->event_date = $demand->event_date;
                $prestation->hall_id = $demand->hall_id;
                $prestation->save();
            }
            $demand->load('customer.demands.eventtype','customer.prestations.eventtype','customer.prestations.demand','prestation.demand',"eventtype",'hall');
            return response()->json($demand, 201);
        }else{


            $demand = new Demand();
            $demand->customer_id = $customer->id;
            $demand->service = $request->get('service');
            $demand->source = $request->get('source',"Site Internet");
            if($demand->source != "Site Internet") {
                $demand->source = $request->user()->name;
            }
            $demand->hall_id = $request->get('hall_id');
            $demand->event_type = $event;
            $demand->event_type_id = $request->get("event_type_id",$eventType->id ?? null);
            $demand->reception_start_time = $request->get('reception_start_time');
            $demand->reception_period = $request->get('reception_period');
            $demand->event_location = $request->get('event_location');
            $demand->number_people = $request->get('number_people');
            $demand->comment = $request->get('comment');
            $demand->commentaire = $request->get('commentaire');
            $demand->event_date = $request->get('event_date');
            $demand->demand_date = $request->get('demand_date', Carbon::now());

            $demand->save();
            $demand->load('customer.demands.eventtype','customer.prestations.eventtype','customer.prestations.demand.eventtype','prestation.demand',"hall","eventtype");

            return response()->json($demand, 201);
        }

    }

    public function destroy(Request $request,$id) {
        Demand::find($id)->delete();
        return response()->json(null, 204);
    }

}
