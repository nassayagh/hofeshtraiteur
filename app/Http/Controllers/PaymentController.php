<?php

namespace App\Http\Controllers;

use App\PaymentTrait;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Prestation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentController
{


    public function __construct() {

    }
    public function index(Request $request) {
        $date = $request->get("date",null);
        if($date != null) {
            $date1 = Carbon::parse($date[0])->startOfDay()->format('Y-m-d');
            $date2 = Carbon::parse($date[1])->endOfDay()->format('Y-m-d');
            //return [$date1,$date2];
            //$query->whereBetween('payment_date', [$date1, $date2]);
        }


        return Payment::query()
            ->where(function ($query) use ($request) {


                $date = $request->get("date",null);
                if($date != null) {
                    $date1 = Carbon::parse($date[0])->subDays(1);
                    $date2 = Carbon::parse($date[1]);


                   $query->whereBetween('payment_date', [$date1, $date2]);
                }
                $method = $request->get("payment_method",null);
                if($method != null) {
                    $query->whereIn('payment_method_id', $method);
                }
                    $query->whereHas('prestation',function ($query) use ($request) {
                        $term = $request->get("search","");
                        $event_type = $request->get("event_type",null);
                        $method = $request->get("payment_method",null);
                        if($event_type != null) {
                            $query->whereIn('event_type_id', $event_type);
                        }

                        if(!empty($term)) {
                            $query->whereHas('customer', function ($query) use ($term) {

                                $query->where("firstname", "like", "%$term%");
                                $query->orWhere("lastname", "like", "%$term%");
                                $query->orWhere("phone", "like", "%$term%");
                                $query->orWhere("email", "like", "%$term%");
                            });
                        }
                    });



            })
            ->with('prestation.customer')
            ->orderBy($request->get("order_by","id"),$request->get("direction","desc"))
            ->paginate($request->get("per_page",10 ));
    }
    public function stats(Request $request) {
        $date = $request->get("date",null);
        if($date != null) {
            $date1 = Carbon::parse($date[0])->startOfDay()->format('Y-m-d');
            $date2 = Carbon::parse($date[1])->endOfDay()->format('Y-m-d');
            //return [$date1,$date2];
            //$query->whereBetween('payment_date', [$date1, $date2]);
        }


        $payments = Payment::query()
            ->where(function ($query) use ($request) {


                $date = $request->get("date",null);
                if($date != null) {
                    $date1 = Carbon::parse($date[0])->subDays(1);
                    $date2 = Carbon::parse($date[1]);


                   $query->whereBetween('payment_date', [$date1, $date2]);
                }
                $method = $request->get("payment_method",null);
                if($method != null) {
                    $query->whereIn('payment_method_id', $method);
                }

                    $query->whereHas('prestation',function ($query) use ($request) {
                        $term = $request->get("search","");
                        $event_type = $request->get("event_type",null);

                        if($event_type != null) {
                            $query->whereIn('event_type_id', $event_type);
                        }

                        if(!empty($term)) {
                            $query->whereHas('customer', function ($query) use ($term) {

                                $query->where("firstname", "like", "%$term%");
                                $query->orWhere("lastname", "like", "%$term%");
                                $query->orWhere("phone", "like", "%$term%");
                                $query->orWhere("email", "like", "%$term%");
                            });
                        }
                    });



            })
            ->with('prestation.customer')
            ->orderBy($request->get("order_by","id"),$request->get("direction","desc"));
        return [
            "total_amount" => $payments->sum("amount"),
            "data" => $payments->paginate($request->get("per_page",10 ))
        ];
    }




}
