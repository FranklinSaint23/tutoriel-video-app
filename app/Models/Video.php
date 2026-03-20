<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory, SoftDeletes; // Active la suppression douce

    protected $fillable = [
        'title', 'slug', 'description', 'video_url', 
        'thumbnail_url', 'level', 'category_id', 'user_id', 'is_published'
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

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'video_user')->withTimestamps();
    }
}
