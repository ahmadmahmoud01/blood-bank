<?php

namespace App\Http\Controllers;

use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $governorates = Governorate::all();
        return view('governorates.index', compact('governorates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('governorates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request_data = $request->validate([
            'name' => 'required|string'
        ],[
           'name.required' => 'please enter the name'
        ]);

        Governorate::create($request_data);

        return redirect()->route('governorates.index')->with('create', 'Governorate created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Governorate $governorate)
    {
        return view('governorates.edit',compact('governorate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Governorate $governorate)
    {
        $request_data = $request->validate([
            'name' => 'required|string'
        ],[
           'name.required' => 'please enter the name'
        ]);

        $governorate->update($request_data);

        return back()->with('update', 'Governorate updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Governorate $governorate)
    {
        $governorate->delete();

        return back()->with('delete', 'Governorate deleted !');
    }
}
