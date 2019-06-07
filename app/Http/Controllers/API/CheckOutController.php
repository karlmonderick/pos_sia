<?php

namespace App\Http\Controllers\API;

use App\CheckOut;
use App\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckOutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $year = date('Y');
        // $month = date('m');
        // $day = date('d');
        // $c_count = CheckOut::whereDate('created_at', Carbon::today())->count() + 1;
        // $receipt_no = $year.''.$month.''.$day.''.$c_count;

        $id = 15;
        // $inventory = DB::table('inventories')
        // ->where('id',$id)
        // ->select('stock_quantity')
        // ->first();

        $cart = DB::table('check_outs')
        ->where('id','=',$id)
        ->first();
        $cart_item = json_decode($cart->cart);

        foreach($cart_item as $c_i){
            $inventory = DB::table('inventories')
            ->where('product_id',$c_i->id)
            ->select('stock_quantity')
            ->first();

            $less = $inventory->stock_quantity - $c_i->qty;
            $inventory->stock_quantity = $less;
            // echo $c_i->qty;
            echo $inventory->stock_quantity;
        }





        // $i = $inventory->stock_quantity;
        // return $cart_item;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request['client_id'] == NULL){
            return 'error';
        }


        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $c_count = CheckOut::whereDate('created_at', Carbon::today())->count() + 1;
        $receipt_no = $year.''.$month.''.$day.''.$c_count;

        $cart = json_decode($request['cart_content']);

        foreach ($cart as $c_i){
            $inventory = Inventory::where('id',$c_i->inventory_id)
            ->where('branch_id', $request['branch_id'])
            ->where('stock_quantity', '>=', $c_i->qty)
            ->first();

            if($inventory == null){
                return abort(422, "Stock not found!");
            } else{
                $less = $inventory->stock_quantity - $c_i->qty;
                $inventory->stock_quantity = $less;
                $inventory->save();
            }


            // $inventory->update(['stock_quantity' => $less]);
            // // echo $c_i->qty;
            // echo $less;
        }

        return CheckOut::create([
            'receipt_no' => $receipt_no,
            'client_id' => $request['client_id'],
            'cart' => $request['cart_content'],
            'total_amount' => $request['total_amount'],
            'branch_id' => $request['branch_id'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CheckOut  $checkOut
     * @return \Illuminate\Http\Response
     */
    public function show(CheckOut $checkOut)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CheckOut  $checkOut
     * @return \Illuminate\Http\Response
     */
    public function edit(CheckOut $checkOut)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CheckOut  $checkOut
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CheckOut $checkOut)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CheckOut  $checkOut
     * @return \Illuminate\Http\Response
     */
    public function destroy(CheckOut $checkOut)
    {
        //
    }
}
