<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {

        return view('categories.index', ['categories' => Category::all()]);

    }

    public function store(Request $request)
    {
        $request_data = $request->validate([
            'name' => 'required|string'
        ],[
           'name.required' => 'please enter the name'
        ]);

        Category::create($request_data);

        return redirect()->route('categories.index')->with('create', 'Category created!');
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
    public function edit(Category $category)
    {
        return view('categories.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request_data = $request->validate([
            'name' => 'required|string'
        ],[
           'name.required' => 'please enter the name'
        ]);

        $category->update($request_data);

        return back()->with('update', 'Category updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('delete', 'Category deleted !');
    }
}
