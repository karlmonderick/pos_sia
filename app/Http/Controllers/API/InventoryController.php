<?php

namespace App\Http\Controllers\API;

use App\Inventory;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return Inventory::orderBy('id', 'DESC')->paginate(5);
        $inventory_product = DB::table('inventories')
        ->join('products', 'inventories.product_id', '=', 'products.id')
        ->join('users', 'inventories.user_id', '=', 'users.id')
        ->select('inventories.*', 'products.name', 'users.name as username')
        ->orderBy('products.name','DESC')
        ->paginate(5);


        $inventory_summary  = DB::table('inventories')
        ->join('products', 'inventories.product_id', '=', 'products.id')
        ->selectRaw('sum(stock_quantity) as stock, products.name')
        ->groupBy('products.name')
        ->get();

        $product_list = Product::get();

        // $inventory_summary  = DB::table('inventories')
        //     ->select(DB::raw('count(*) as num'))
        //     ->groupBy('product_id')
        //     ->get();

        return (['inventory_product'=>$inventory_product, 'inventory_summary'=>$inventory_summary, 'product_list' => $product_list]);


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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function show(Inventory $inventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventory $inventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inventory $inventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventory $inventory)
    {
        //
    }
}
