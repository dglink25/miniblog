<?php
// app/Http/Controllers/NotificationController.php
namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function unreadCount() {
        return ['count'=>auth()->user()->unreadNotifications()->count()];
    }

    public function show($id) {
        $n = auth()->user()->notifications()->findOrFail($id);
        if ($n->unread()) $n->markAsRead();
        $url = $n->data['url'] ?? route('articles.index');
        return redirect($url);
    }
}
