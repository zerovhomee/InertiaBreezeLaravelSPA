<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentVoteController extends Controller
{
    public function vote(Comment $comment, Request $request)
    {
        try {
            $request->validate([
                'vote' => 'required|integer|in:1,-1'
            ]);

            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to vote'
                ], 401);
            }

            // Используем updateOrCreate для избежания дубликатов
            $vote = CommentVote::updateOrCreate(
                [
                    'comment_id' => $comment->id,
                    'user_id' => auth()->id()
                ],
                [
                    'vote' => $request->vote
                ]
            );

            // Пересчитываем общее количество голосов
            $votesCount = $comment->votes()->sum('vote');

            return response()->json([
                'success' => true,
                'votes_count' => $votesCount,
                'message' => 'Vote recorded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error voting: ' . $e->getMessage()
            ], 500);
        }
    }

}
