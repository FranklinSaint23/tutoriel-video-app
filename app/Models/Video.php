<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Video extends Model
{
    use SoftDeletes; // Active la suppression douce

    protected $fillable = [
        'title', 'slug', 'description', 'video_url', 
        'thumbnail_url', 'level', 'category_id', 'user_id'
    ];

    /**
     * La catégorie à laquelle appartient la vidéo.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * L'utilisateur qui a publié la vidéo.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
