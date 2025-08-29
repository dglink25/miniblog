<?php 
// app/Http/Controllers/AnnouncementPublicController.php
namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnnouncementPublicController extends Controller
{
    public function dismiss(Announcement $announcement) {
        DB::table('announcement_user_dismissals')->updateOrInsert([
            'announcement_id'=>$announcement->id,
            'user_id'=>auth()->id(),
        ], ['dismissed_at'=>now()]);
        return back()->with('success','Annonce masquée pour vous');
    }
}