<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id',
        'stars',
    ];

    /**
     * L'utilisateur qui a donné la note.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * La vidéo qui reçoit la note.
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
}