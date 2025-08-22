<?php
namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function show($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);

        if ($notification->unread()) {
            $notification->markAsRead();
        }

        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }

        return redirect()->back()->with('status', 'Notification ouverte.');
    }
}
