<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        // if(\Gate::allows('isAdmin')){
            return Product::orderBy('id', 'DESC')->paginate(5);
        // }
    }
}
