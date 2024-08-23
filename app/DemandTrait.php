<?php

namespace App;

use App\Models\Customer;
use Illuminate\Http\Request;

trait DemandTrait
{
    public function storeCustomer(Request $request)
    {
        $inputs = $request->all();
        $append = "";
        $oldCustomer = null;
        if($request->has("customer")) {
            $cus = $request->get("customer");
            $oldCustomer = Customer::query()->where('email',$cus["email"])->first();
        }else{

            $oldCustomer = Customer::query()->where('email',$request->get("email"))->first();
        }




        if(!$oldCustomer){
            if($request->has("customer")) {
                $Customer = Customer::create($request->get("customer"));

                return $Customer;
            }else{
                $Customer = new Customer();
                $Customer->firstname = $request->get($append.'firstname');
                $Customer->lastname = $request->get($append.'lastname');
                $Customer->phone = $request->get($append.'phone');
                $Customer->is_customer = 0;
                $Customer->email = $request->get($append.'email');
                $Customer->save();
                return $Customer;
            }

        }else{
            if($request->has("customer")) {
                $oldCustomer->update($request->get("customer"));

                return $oldCustomer;
            }else{
                $Customer = $oldCustomer;
                $Customer->firstname = $request->get($append.'firstname');
                $Customer->lastname = $request->get($append.'lastname');
                $Customer->phone = $request->get($append.'phone');
                $Customer->is_customer = $request->get($append.'is_customer',0);
                $Customer->email = $request->get($append.'email');
                $Customer->save();
                return $Customer;
            }

        }


    }
}
