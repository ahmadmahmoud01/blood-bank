<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Governorate;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index() {

        return view('cities.index', [
            'cities'       => City::with('governorate')->get(),
            'governorates' => Governorate::all(),
        ]);

    }// end of index

    public function create() {

        return view('cities.create');

    }// end of create

    public function store(Request $request) {

        $request_data = $request->validate([
            'name' => 'required|string',
            'governorate_id' => 'required|exists:governorates,id'
        ],[
           'name.required' => 'please enter the name',
        ]);

        City::create($request_data);

        return redirect()->route('cities.index')->with('create', 'City created!');

    }// end of store

    public function edit(City $city) {

        $governorates = Governorate::all();
        return view('cities.edit', compact('city', 'governorates'));

    }// end of edit

    public function update(Request $request, City $city) {

        $request_data = $request->validate([
            'name' => 'required|string',
            'governorate_id' => 'required|exists:governorates,id'
        ],[
           'name.required' => 'please enter the name',
        ]);

        $city->update($request_data);

        return back()->with('update', 'City updated!');

    }// end of update

    public function destroy(City $city) {
        $city->delete();

        return back()->with('delete', 'City deleted !');
    }
}
