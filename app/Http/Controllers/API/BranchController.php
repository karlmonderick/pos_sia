<?php

namespace App\Http\Controllers\API;

use App\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branch = DB::table('branches')
        ->paginate(5);
        return $branch;
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
            'location' => 'required|string|max:255',
        ]);
        return Branch::create([
            'name' => $request['name'],
            'location' => $request['location'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $branch->update($request->all());

        return $id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();
        return ['message' => 'product deleted'];
    }

    public function search(){
        if($search = \Request::get('q')){
            $branch = Branch::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('location','LIKE', "%$search%");
            })->paginate(5);
        }else{
            $branch = Branch::orderBy('id', 'DESC')->paginate(5);
        }
        return $branch;
    }
}
