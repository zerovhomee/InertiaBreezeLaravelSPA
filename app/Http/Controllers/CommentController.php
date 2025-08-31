<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        // Валидация
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // Создание комментария
        $comment = new Comment();
        $comment->content = $validated['content'];
        $comment->post_id = $post->id;
        $comment->user_id = Auth::id();
        $comment->save();

        // Загружаем отношения для возврата на фронтенд
        $comment->load('user');

        return back()->with('success', 'Comment added successfully!');
    }

}
