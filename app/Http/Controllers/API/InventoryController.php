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
        ->join('products_categories', 'products.category_id', '=', 'products_categories.id')
        ->select('inventories.*', 'products.name', 'users.name as username', 'products_categories.category_name')
        ->orderBy('inventories.id','ASC')
        ->paginate(5);


        $inventory_summary  = DB::table('inventories')
        ->join('products', 'inventories.product_id', '=', 'products.id')
        ->selectRaw('sum(stock_quantity) as stock, products.name')
        ->groupBy('products.name')
        ->get();

        $product_list = Product::get();

        $product_category_summary = DB::table('products_categories')
        ->join('products', 'products.category_id', '=', 'products_categories.id')
        ->selectRaw('count(*) as c_qty, products.name, products_categories.*')
        ->groupBy('products_categories.category_name')
        ->get();

        // $inventory_summary  = DB::table('inventories')
        //     ->select(DB::raw('count(*) as num'))
        //     ->groupBy('product_id')
        //     ->get();

        return (['inventory_product'=>$inventory_product, 'inventory_summary'=>$inventory_summary, 'product_list' => $product_list, 'product_category_summary' => $product_category_summary]);


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
        $this->validate($request, [
            'stock_quantity' => 'required|numeric|gt:0',
        ]);
        return Inventory::create([
            'product_id' => $request['product_id'],
            'stock_quantity' => $request['stock_quantity'],
            'stock_original' => $request['stock_quantity'],
            'user_id' => $request['user_id'],
        ]);
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
    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $this->validate($request, [
            'stock_quantity' => 'required|numeric|gt:0|lte:'.$request['stock_original'],
            'stock_original' => 'required|numeric|gte: 1,'.$request['stock_quantity'],

        ]);

        $inventory->update($request->all());

        return $id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         // $this->authorize('isAdmin');
         $inventory = Inventory::findOrFail($id);
         $inventory->delete();
         return ['message' => 'Inventory info Deleted'];
    }


    public function search(){
        if($search = \Request::get('q')){

            // $inventories = Inventory::where(function($query) use ($search){
            //     $query->where('name','LIKE',"%$search%");
            // })->paginate(5);

            $inventory_product = DB::table('inventories')
            ->join('products', 'inventories.product_id', '=', 'products.id')
            ->join('users', 'inventories.user_id', '=', 'users.id')
            ->join('products_categories', 'products.category_id', '=', 'products_categories.id')
            ->select('inventories.*', 'products.name', 'users.name as username', 'products_categories.category_name')
            ->orderBy('inventories.id','ASC')
            ->where('products.name','LIKE',"%$search%")
            ->paginate(5);

        }else{
            $inventory_product = DB::table('inventories')
            ->join('products', 'inventories.product_id', '=', 'products.id')
            ->join('users', 'inventories.user_id', '=', 'users.id')
            ->join('products_categories', 'products.category_id', '=', 'products_categories.id')
            ->select('inventories.*', 'products.name', 'users.name as username', 'products_categories.category_name')
            ->orderBy('inventories.id','ASC')
            ->paginate(5);
        }
        return $inventory_product;
    }
    public function searchByCategory(){
        if($search = \Request::get('q')){

            $inventory_product = DB::table('inventories')
            ->join('products', 'inventories.product_id', '=', 'products.id')
            ->join('users', 'inventories.user_id', '=', 'users.id')
            ->join('products_categories', 'products.category_id', '=', 'products_categories.id')
            ->select('inventories.*', 'products.name', 'users.name as username', 'products_categories.category_name')
            ->orderBy('inventories.id','ASC')
            ->where('products.category_id','LIKE', $search)
            ->paginate(10);

        }else{
            $inventory_product = DB::table('inventories')
            ->join('products', 'inventories.product_id', '=', 'products.id')
            ->join('users', 'inventories.user_id', '=', 'users.id')
            ->join('products_categories', 'products.category_id', '=', 'products_categories.id')
            ->select('inventories.*', 'products.name', 'users.name as username', 'products_categories.category_name')
            ->orderBy('inventories.id','ASC')
            ->paginate(5);
        }
        return $inventory_product;
    }
}
