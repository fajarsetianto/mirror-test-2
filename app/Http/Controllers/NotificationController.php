<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification as Notification;

class NotificationController extends Controller
{

    public function read(Notification $notification)
    {
        $notification->markAsRead();
        return redirect()->to($notification->data['url']);
    }

    public function markAllRead($guard){
        if(!auth($guard)->check()){
            return abort(403);
        }
        auth($guard)->user()->unreadNotifications()->update(['read_at' => now()]);

        return \redirect()->back();
    }
}
