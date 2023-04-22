<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index() {

        return view('posts.index', [
            'posts' => Post::all(),
            'categories' => Category::all()
        ]);

    }// end of index

    public function store(Request $request) {

        $request_data = $request->validate([
            'title'         => 'required',
            'content'       => 'required',
            'category_id'   => 'required',
        ], [
            'category_id' => 'please enter the category'
        ]);

        Post::create($request_data);

        return redirect()->route('posts.index')->with('create', 'Post created!');

    }// end of store

    public function edit(Post $post) {

        return view('posts.edit', [
            'post' => $post,
            'categories' => Category::all()
        ]);

    }// end of edit

    public function update(Request $request, Post $post) {

        $request_data = $request->validate([
            'title'         => 'required',
            'content'       => 'required',
            'category_id'   => 'required',
        ], [
            'category_id' => 'please enter the category'
        ]);

        $post->update($request_data);

        return back()->with('update', 'Post updated!');


    }// end of update

    public function destroy(Post $post) {

        $post->delete();

        return back()->with('delete', 'Post deleted!');

    }


}
