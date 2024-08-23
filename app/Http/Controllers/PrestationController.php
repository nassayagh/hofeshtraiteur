<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Demand;
use App\Models\EventType;
use App\Models\Payment;
use App\Models\Prestation;
use App\Models\PrestationService;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PrestationController
{

    const _RELATIONS = ['customer.demands.eventtype', 'customer.prestations.demand.eventtype', 'demand.eventtype', 'services', 'payments', "hall",'eventtype'];

    public function __construct()
    {

    }

    public function index(Request $request)
    {
        /*foreach (Prestation::all() as $prestation){
            if($prestation->comment != $prestation->commentaire ){
                $prestation->comment .= "\n".$prestation->commentaire;
            }
        }*/

        $date = $request->get("date", null);
        if ($date != null) {
            $date1 = Carbon::parse($date[0])->startOfDay()->format('Y-m-d');
            $date2 = Carbon::parse($date[1])->endOfDay()->format('Y-m-d');
            //return [$date1,$date2];
            //$query->whereBetween('demand_date', [$date1, $date2]);
        }
        $prestions = Prestation::select("prestations.*");

        if ($request->has("statistics")) {

            $prestions = $prestions->where("status", ">=", Prestation::CREATED)
                ->where("service_total",">",0);
            if ($request->get("statistics") == "future") {
                $prestions = $prestions->where("event_date", ">=", Carbon::now());
            }
            if ($request->get("statistics") == "past") {
                $prestions = $prestions->where("event_date", "<", Carbon::now()->startOfDay());
            }
            if ($request->get("statistics") == "closed") {
                $prestions = $prestions
                    /*->where("event_date", "<", Carbon::now()->startOfDay())*/
                        ->where("service_total",">",0)
                ->where(function ($query) {
                    $query
                        ->whereRaw("payment_total < service_total")
                        ->orWhere("payment_total", "<=", 0);
                });

                /*$prestions = $prestions->groupBy("prestations.id")->having(DB::raw("(
                    select
                      sum(`payments`.`amount`)
                    from
                      `payments`
                    where
                      `prestations`.`id` = `payments`.`prestation_id` AND payments.deleted_at is null
                  )"), "<", DB::raw("(
                    select
                      sum(`prestation_services`.`total`)
                    from
                      `prestation_services`
                    where
                      `prestations`.`id` = `prestation_services`.`prestation_id` AND prestation_services.deleted_at is null
                  )"));*/
            }


        } else {
            $prestions = $prestions
                ->where(function ($query) use ($request) {

                    $term = $request->get("search", "");
                    $event_type = $request->get("event_type", null);
                    $date = $request->get("date", null);
                    $status = $request->get("status", null);

                    $hall = $request->get("hall", null);

                    if ($status != null || $status === 0) {
                        if ($status == Prestation::VALIDATED) {
                            $query->where("status", "=", Prestation::VALIDATED);
                            //$query->where("event_date", ">=", Carbon::now());
                        }
                        if ($status == Prestation::CLOSED) {
                            $query->where("status", ">=", Prestation::CREATED);
                            $query->where("event_date", "<", Carbon::now()->startOfDay());
                        }
                        if ($status == Prestation::CANCELLED) {
                            $query->where("status", "=", Prestation::CANCELLED);
                        }

                    } else {
                        //$query->whereNull('status')->orWhere('status', 0);
                    }
                    if ($date != null) {
                        $date1 = Carbon::parse($date[0])->startOfDay();
                        $date2 = Carbon::parse($date[1])->endOfDay();

                        $query->whereBetween('event_date', [$date1, $date2]);
                    }
                    if ($event_type != null) {
                        $query->whereIn('event_type_id', $event_type);
                        //$query->whereIn('event_type', $event_type);
                    }
                    if ($hall != null) {
                        $query->whereIn('hall_id', $hall);
                    }

                    if (!empty($term)) {

                        $query->where("service", "like", "%$term%");
                        $query->orWhere("comment", "like", "%$term%");
                        $query->orWhereHas('customer', function ($query) use ($term) {
                            $query->where("firstname", "like", "%$term%");
                            $query->orWhere("lastname", "like", "%$term%");
                            $query->orWhere("phone", "like", "%$term%");
                            $query->orWhere("email", "like", "%$term%");
                        });
                    }

                });
        }
        $payment_status = $request->get("payment_status", null);
        if ($payment_status != null) {

            if ($payment_status == "completed") {
                $prestions = $prestions->whereRaw("payment_total >= service_total");
                /*$prestions = $prestions->groupBy("prestations.id")->having(DB::raw("(
                    select
                      sum(`payments`.`amount`)
                    from
                      `payments`
                    where
                      `prestations`.`id` = `payments`.`prestation_id` AND payments.deleted_at is null
                  )"), ">=", DB::raw("(
                    select
                      sum(`prestation_services`.`total`)
                    from
                      `prestation_services`
                    where
                      `prestations`.`id` = `prestation_services`.`prestation_id` AND prestation_services.deleted_at is null
                  )"));*/
            } else {
                $prestions = $prestions->whereRaw("payment_total < service_total");

                /*$prestions = $prestions->groupBy("prestations.id")->having(DB::raw("(
                    select
                      sum(`payments`.`amount`)
                    from
                      `payments`
                    where
                      `prestations`.`id` = `payments`.`prestation_id` AND payments.deleted_at is null
                  )"), "<", DB::raw("(
                    select
                      sum(`prestation_services`.`total`)
                    from
                      `prestation_services`
                    where
                      `prestations`.`id` = `prestation_services`.`prestation_id` AND prestation_services.deleted_at is null
                  )"));*/
            }
        }

        $prestions = $prestions->with(self::_RELATIONS)->withSum("payments", "amount")->withSum("services", "total");

        if ($request->has("statistics")) {
            $prestions = $prestions->orderBy($request->get("order_by", "id"), $request->get("direction", "desc"));
        } else {
            $prestions = $prestions->orderBy($request->get("order_by", "id"), $request->get("direction", "desc"));
        }

        $prestions = $prestions->paginate($request->get("per_page", 10));

        return $prestions;
    }
    public function stats(Request $request)
    {


        $prestions = Prestation::select("prestations.*");

        if ($request->has("statistics")) {

            $prestions = $prestions->where("status", ">=", Prestation::CREATED)
                /*->where("service_total",">",0)*/;
            if ($request->get("statistics") == "future") {
                $prestions = $prestions->where("event_date", ">=", Carbon::now());
            }
            if ($request->get("statistics") == "past") {
                $prestions = $prestions->where("event_date", "<", Carbon::now()->startOfDay());
            }
            if ($request->get("statistics") == "closed") {
                $prestions = $prestions
                    /*->where("event_date", "<", Carbon::now()->startOfDay())*/
                        ->where("service_total",">",0)
                ->where(function ($query) {
                    $query
                        ->whereRaw("payment_total < service_total")
                        ->orWhere("payment_total", "<=", 0);
                });

                /*$prestions = $prestions->groupBy("prestations.id")->having(DB::raw("(
                    select
                      sum(`payments`.`amount`)
                    from
                      `payments`
                    where
                      `prestations`.`id` = `payments`.`prestation_id` AND payments.deleted_at is null
                  )"), "<", DB::raw("(
                    select
                      sum(`prestation_services`.`total`)
                    from
                      `prestation_services`
                    where
                      `prestations`.`id` = `prestation_services`.`prestation_id` AND prestation_services.deleted_at is null
                  )"));*/
            }


        }


        $prestions = $prestions->with(self::_RELATIONS)->withSum("payments", "amount")->withSum("services", "total");


        $prestions = $prestions->orderBy($request->get("order_by", "id"), $request->get("direction", "desc"));


        $count = $prestions->count();
        $total_amount = $prestions->sum("service_total");
        $prestions = $prestions->take(10)->get();

        return [
            "data" => $prestions,
            "total" => $count,
            "total_amount" => $total_amount,
        ];
    }

    public function statistics(Request $request)
    {

        if ($request->has("statistics")) {
            if ($request->get("statistics") == "month_income") {
                $data = [];
                // ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')

                $year = $request->get("year", date('Y'));

                $month = date("m");

                $section = $request->get("section", "future");
                if ($section == "future") {
                    $m = (int)$month;
                    if (date('Y') != $year) {
                        $m = 1;
                    }
                    for ($i = $m; $i < 13; $i++) {
                        $total_amount = PrestationService::query()->whereHas("prestation", fn($q) => $q->whereYear("event_date", $year)->whereMonth("event_date", $i))->sum("total");
                        if ($total_amount > 0) {
                            $total_paid = Payment::query()->whereHas("prestation", fn($q) => $q->whereYear("event_date", $year)->whereMonth("event_date", $i))->sum("amount");
                            $d1 = Carbon::createFromDate($year, $i,1);
                            $d2 = Carbon::createFromDate($year, $i,1);
                            $data[] = [
                                "date1" => $d1->startOfMonth()->toDate()->format("Y-m-d"),
                                "date2" => $d2->endOfMonth()->toDate()->format("Y-m-d"),
                                "label" => Carbon::createFromDate($year, $i),
                                "amount_left" => $total_amount - $total_paid,
                                "amount_paid" => $total_paid,
                                "total_amount" => $total_amount,
                                "customers_count" => Customer::whereHas("prestations", fn($q) => $q->whereYear("event_date", $year)->whereMonth("event_date", $i))->count(),
                                "prestations_closed" => Prestation::where("status", '>=', Prestation::CREATED)->whereMonth("event_date", $i)->whereYear("event_date", $year)->count(),
                                "prestations_cancelled" => Prestation::where("status", '=', Prestation::CANCELLED)->whereMonth("event_date", $i)->whereYear("event_date", $year)->count(),
                            ];
                        }

                    }
                } else {
                    $m = (int)$month - 1;
                    if (date('Y') != $year) {
                        $m = 12;
                    }
                    for ($i = $m; $i > 0; $i--) {
                        $date = Carbon::createFromDate($year, $i);
                        $total_amount = PrestationService::query()->whereHas("prestation", fn($q) => $q->whereYear("event_date", $year)->whereMonth("event_date", $i))->sum("total");
                        if ($total_amount > 0) {
                            $total_paid = Payment::query()->whereHas("prestation", fn($q) => $q->whereYear("event_date", $year)->whereMonth("event_date", $i))->sum("amount");
                            $d1 = Carbon::createFromDate($year, $i,1);
                            $d2 = Carbon::createFromDate($year, $i,1);
                            $data[] = [
                                "date1" => $d1->startOfMonth()->toDate()->format("Y-m-d"),
                                "date2" => $d2->endOfMonth()->toDate()->format("Y-m-d"),
                                "label" => Carbon::createFromDate($year, $i),
                                "amount_left" => $total_amount - $total_paid,
                                "amount_paid" => $total_paid,
                                "total_amount" => $total_amount,
                                "customers_count" => Customer::whereHas("prestations", fn($q) => $q->whereYear("event_date", $year)->whereMonth("event_date", $i))->count(),
                                "prestations_closed" => Prestation::where("status", '>=', Prestation::CREATED)->whereMonth("event_date", $i)->whereYear("event_date", $year)->count(),
                                "prestations_cancelled" => Prestation::where("status", '=', Prestation::CANCELLED)->whereMonth("event_date", $i)->whereYear("event_date", $year)->count(),
                            ];
                        }

                    }
                }


                return $data;
            } else {
                $data = [];
                // ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
                $years = Prestation::query()->selectRaw('YEAR(event_date) as year')->orderBy("year","DESC")->distinct(["year"])->get();
                foreach ($years as $p) {
                    if($p->year) {
                        $year = $p->year;
                        $total_amount = PrestationService::query()->whereHas("prestation", fn($q) => $q->whereYear("event_date", $year))->sum("total");
                        $total_paid = Payment::query()->whereHas("prestation", fn($q) => $q->whereYear("event_date", $year))->sum("amount");
                        $d = Carbon::createFromDate($year,1,1);
                        $d2 = Carbon::createFromDate($year,1,1);
                        $data[] = [
                            "date1" => $d->startOfYear(),
                            "date2" => $d2->endOfYear(),
                            "year" => $year,
                            "amount_left" => $total_amount - $total_paid,
                            "amount_paid" => $total_paid,
                            "total_amount" => $total_amount,
                            "customers_count" => Customer::whereHas("prestations", fn($q) => $q->whereYear("event_date", $year))->count(),
                            "prestations_closed" => Prestation::where("status", '>=', Prestation::CREATED)->whereYear("event_date", $year)->count(),
                            "prestations_cancelled" => Prestation::where("status", '=', Prestation::CANCELLED)->whereYear("event_date", $year)->count(),
                        ];
                    }

                }
                return $data;
            }

        } else {
            $totalPayable = PrestationService::query()->whereHas("prestation", fn($q) => $q->where("status", ">=", Prestation::VALIDATED))->sum("total");
            $payments = Payment::query()->sum("amount");
            $paymentLeft = $totalPayable - $payments;
            return [
                "total" => Prestation::query()->count(),
                "validation" => Prestation::query()->where("status", "=", Prestation::CREATED)->count(),
                "validated" => Prestation::query()->where("status", "=", Prestation::VALIDATED)->count(),
                "processing" => Prestation::query()->where("status", "=", Prestation::PROCESSING)->count(),
                "closed" => Prestation::query()->where("status", "=", Prestation::CLOSED)->count(),
                "cancelled" => Prestation::query()->where("status", "=", Prestation::CANCELLED)->count(),
                "paymentsLeft" => $paymentLeft,
                "paymentsReceived" => $payments,
                "totalPayments" => $totalPayable,
            ];
        }

    }

    public function show(Request $request, $id)
    {
        return Prestation::query()->whereId($id)->with(self::_RELATIONS)->withSum("payments", "amount")->withSum("services", "total")->first();
    }

    public function addComment(Request $request, $id)
    {
        $prestation = Prestation::query()->whereId($id)->with(self::_RELATIONS)->first();
        $prestation->commentaire = $request->get('comment');
        $prestation->comment = $request->get('comment');
        $prestation->save();
        return $prestation;
    }

    public function cancelPrestation(Request $request, $id)
    {
        $prestation = Prestation::query()->whereId($id)->with(self::_RELATIONS)->first();
        $prestation->status = Prestation::CANCELLED;
        $prestation->cancel_comment = $request->get('comment');
        $prestation->cancelled_date = Carbon::now();
        $prestation->save();
        return $prestation;
    }

    public function validatePrestation(Request $request, $id)
    {
        $prestation = Prestation::query()->whereId($id)->with(self::_RELATIONS)->first();
        if ($prestation->status == Prestation::CANCELLED) {
            $prestation->comment = $request->get("comment");
            $prestation->status = Prestation::CREATED;
        } else {
            $prestation->status = Prestation::VALIDATED;
        }

        $prestation->validated_date = Carbon::now();

        $prestation->save();
        $customer = $prestation->customer;
        $customer->is_customer = 1;
        $customer->save();
        //$prestation->load(self::_RELATIONS);

        return $prestation;
    }

    public function changeHall(Request $request, $id)
    {

        if($request->get("type", "prestation") == "prestation") {
            $prestation = Prestation::query()->whereId($id)->first();
            $prestation->hall_id = $request->get("hall");
            $prestation->save();

            return Prestation::query()->whereId($id)->with(self::_RELATIONS)->first();
        }else{
            $prestation = Demand::query()->whereId($id)->first();
            $prestation->hall_id = $request->get("hall");
            $prestation->save();

            return Demand::query()->whereId($id)->with('customer.demands','customer.prestations.demand','prestation.demand',"hall")->first();
        }


    }

    public function startPrestation(Request $request, $id)
    {
        $prestation = Prestation::query()->whereId($id)->with(self::_RELATIONS)->first();
        $prestation->status = Prestation::PROCESSING;
        $prestation->start_date = Carbon::now();
        $prestation->save();
        //$prestation->load(self::_RELATIONS);

        return $prestation;
    }

    public function processedPrestation(Request $request, $id)
    {
        $prestation = Prestation::query()->whereId($id)->with(self::_RELATIONS)->first();
        $prestation->status = Prestation::CLOSED;
        $prestation->save();
        //$prestation->load(self::_RELATIONS);

        return $prestation;
    }

    public function closePrestation(Request $request, $id)
    {
        $prestation = Prestation::query()->whereId($id)->with(self::_RELATIONS)->first();
        $prestation->status = Prestation::CLOSED;
        $prestation->closed_date = Carbon::now();
        $prestation->save();
        //$prestation->load(self::_RELATIONS);

        return $prestation;
    }

    public function addService(Request $request, $id)
    {
        $prestation = Prestation::query()->findOrFail($id)->load(["customer.demands"]);

        $inputs = request()->all();
        $inputs["prestation_id"] = $id;
        $inputs["total"] = ($inputs["quantity"] ?? 0) * ($inputs['price'] ?? 0);

        if (!empty($inputs["id"])) {
            $service = PrestationService::find($inputs['id'])->update($inputs);
        } else {
            $service = PrestationService::where("service_id", "=", $inputs["service_id"])->where('prestation_id',$id)->first();
            if($service){
                $service->update($inputs);
            }else{
                $service = PrestationService::create($inputs);
            }

        }
        $prestation->service_total = $prestation->services->sum("total");
        $prestation->save();
        $prestation->load(self::_RELATIONS);
        return $prestation;
    }

    public function addPayment(Request $request, $id)
    {
        $prestation = Prestation::query()->findOrFail($id)->load(["customer.demands"]);

        $inputs = request()->all();
        $inputs["prestation_id"] = $id;


        if (!empty($inputs["id"])) {

            $service = Payment::find($inputs['id'])->update($inputs);
        } else {
            $service = Payment::create($inputs);
        }
        $prestation->payment_total = $prestation->payments->sum("amount");
        $prestation->save();
        $prestation->load(self::_RELATIONS);
        return $prestation;
    }

    public function store(Request $request)
    {
        $input = $request->all();
        if ($request->filled('id')) {
            $Prestation = Prestation::find($input['id']);
            $validator = Validator::make($request->all(), [
                //'comment' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(["errors" => ["validation" => $validator->errors()]], 422);
            }
        } else {
            $validator = Validator::make($request->all(), [
                //'comment' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(["errors" => ["validation" => $validator->errors()]], 422);
            }
        }


        if (!empty($input["id"])) {
            $prestation = Prestation::find($input['id']);
            //$prestation->comment = $input['comment'];
            /*$prestation->customer_id = $customer->id;*/
            $prestation->service = $request->get('service');
            $prestation->event_type = $request->get('event_type');
            $prestation->event_type_id = $request->get('event_type_id');
            $prestation->reception_start_time = $request->get('reception_start_time');
            $prestation->number_people = $request->get('number_people');
            $prestation->comment = $request->get('comment');
            $prestation->event_date = $request->get('event_date');
            $prestation->demand_date = $request->get('demand_date');
            $prestation->save();

            return response()->json($Prestation, 201);
        } else {

            $customer = $this->storeCustomer($request);
            $prestation = new Prestation();
            $prestation->customer_id = $customer->id;
            $prestation->service = $request->get('service');
            $prestation->event_type = $request->get('event_type');
            $prestation->event_type_id = $request->get('event_type_id');
            $prestation->reception_start_time = $request->get('reception_start_time');
            $prestation->number_people = $request->get('number_people');
            $prestation->comment = $request->get('comment');
            $prestation->event_date = $request->get('event_date');
            $prestation->demand_date = $request->get('demand_date');
            $prestation->save();

            $prestation = Prestation::whereId($prestation->id)->with(self::_RELATIONS)->withSum("payments", "amount")->withSum("services", "total")->first();



            return response()->json($prestation, 201);
        }

    }

    public function destroy(Request $request, $id)
    {
        Prestation::find($id)->delete();
        return response()->json(null, 204);
    }

    public function destroyService(Request $request, $id, $serviceId)
    {
        PrestationService::find($serviceId)->delete();
        $prestation = Prestation::whereId($id)->with(self::_RELATIONS)->withSum("payments", "amount")->withSum("services", "total")->first();
        $prestation->service_total = $prestation->services->sum("total");
        $prestation->save();
        $prestation = Prestation::whereId($id)->with(self::_RELATIONS)->withSum("payments", "amount")->withSum("services", "total")->first();
        return $prestation;
    }

    public function destroyPayment(Request $request, $id, $paymentId)
    {

        Payment::find($paymentId)->delete();
        $prestation = Prestation::whereId($id)->with(self::_RELATIONS)->withSum("payments", "amount")->withSum("services", "total")->first();
        $prestation->payment_total = $prestation->payments->sum("amount");
        $prestation->save();
        $prestation = Prestation::whereId($id)->with(self::_RELATIONS)->withSum("payments", "amount")->withSum("services", "total")->first();
        return $prestation;
    }

}
