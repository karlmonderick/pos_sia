<?php

namespace App\Http\Controllers\API;

use App\InvoiceList;
use App\CheckOut;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class InvoiceListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DB::table('check_outs')
        ->join('branches', 'check_outs.branch_id', '=', 'branches.id')
        ->join('clients', 'check_outs.client_id', '=', 'clients.id')
        ->select('check_outs.*', 'branches.name', DB::raw("CONCAT(clients.first_name, ' ',clients.last_name) AS full_name"))
        ->get(10);
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
     * @param  \App\InvoiceList  $invoiceList
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceList $invoiceList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InvoiceList  $invoiceList
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceList $invoiceList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InvoiceList  $invoiceList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoiceList $invoiceList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InvoiceList  $invoiceList
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $invoice = DB::table('check_outs')
        // ->where('id', $id)
        // ->first();
        // $invoice->delete();
        $product = CheckOut::findOrFail($id);
        $product->delete();
        return ['message' => 'invoice checkout deleted'];
    }
}
