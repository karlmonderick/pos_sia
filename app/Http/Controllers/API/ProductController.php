<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function index()
    {
        // if(\Gate::allows('isAdmin')){
            return Product::orderBy('id', 'DESC')->paginate(5);
        // }
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
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|gt:0',
        ]);
        return Product::create([
            'name' => $request['name'],
            'price' => $request['price'],
            'description' => $request['description'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|gt:0',

        ]);

        $product->update($request->all());

        return $id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $this->authorize('isAdmin');
        $product = Product::findOrFail($id);
        $product->delete();
        return ['message' => 'product deleted'];
    }

    public function search(){
        if($search = \Request::get('q')){
            $products = Product::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('description','LIKE', "%$search%");
            })->paginate(5);
        }else{
            $products = Product::orderBy('id', 'DESC')->paginate(5);
        }
        return $products;
    }
}
