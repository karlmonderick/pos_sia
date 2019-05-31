<?php

namespace App\Http\Controllers\API;

use App\InvoiceList;
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
        return DB::table('check_outs')->get();
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
    public function destroy(InvoiceList $invoiceList)
    {
        //
    }
}
