<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index(){
        $posts = Post::with(['user', 'comments' => function($query) {
            $query->with(['user', 'votes'])
                ->withCount(['votes as rating' => function($query) {
                    $query->select(\DB::raw('COALESCE(SUM(vote), 0)'));
                }])
                ->orderBy('rating', 'desc')
                ->orderBy('created_at', 'desc');
        }])->latest()->get();

        return Inertia::render('Post/Index', compact('posts'));
    }

    public function create(){
        return Inertia::render('Post/Create', []);
    }

    public function store(StoreRequest $request){
        $data = $request->validated();
        $data += ['user_id' => Auth::id()];
        Post::create($data);
        return redirect()->route('posts.index');
    }

    public function userPosts(){
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $posts = Auth::user()->posts;
        return Inertia::render('Post/User', compact('posts'));
    }

    public function edit(Post $post){
        return Inertia::render('Post/Edit', compact('post'));
    }

    public function update(UpdateRequest $request, Post $post){
        $data = $request->validated();
        $post -> update($data);
        return redirect()->route('posts.index');
    }

    public function destroy(Post $post){
        $post -> delete();
        return redirect()->route('posts.index');
    }
}
