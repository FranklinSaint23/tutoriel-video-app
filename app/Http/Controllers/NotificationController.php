<?php

namespace App\Controllers;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function markAllAsRead()
    {
        if (auth()->check()) {
            // Cette méthode remplit la colonne 'read_at' en BDD
            auth()->user()->unreadNotifications->markAsRead();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 403);
    }
}
