<?php

namespace App\Notifications;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewVideoPublished extends Notification
{
    use Queueable;

    protected $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    // On définit le canal de diffusion : ici la base de données
    public function via($notifiable)
    {
        return ['database'];
    }

    // Les données qui seront transformées en JSON dans la colonne 'data'
    public function toArray($notifiable)
    {
        return [
            'video_id' => $this->video->id,
            'title' => $this->video->title,
            'message' => "Un nouveau tuto est disponible : {$this->video->title}",
            'url' => route('videos.show', $this->video->id),
        ];
    }
}