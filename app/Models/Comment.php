<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    public function post(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function votes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CommentVote::class);
    }

    // Атрибут для рейтинга
    public function getRatingAttribute(): int
    {
        return $this->votes()->sum('vote');
    }

    // Проверка, голосовал ли пользователь
    public function getUserVoteAttribute(): ?int
    {
        if (!auth()->check()) {
            return null;
        }

        $vote = $this->votes()->where('user_id', auth()->id())->first();
        return $vote ? $vote->vote : null;
    }
}
