<?php 
// app/Http/Controllers/Admin/AnnouncementController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index() {
        $annonces = Announcement::latest()->paginate(10);
        return view('admin/annonces/index', compact('annonces'));
    }

    public function create() {
        return view('admin/annonces/form', ['annonce'=>new Announcement()]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'title'=>'required|string|max:255',
            'content_html'=>'nullable|string',
            'media_type'=>'required|in:none,image,video',
            'media_url'=>'nullable|string|max:2048',
            'is_published'=>'sometimes|boolean',
            'is_pinned'=>'sometimes|boolean',
        ]);
        $data['created_by'] = auth()->id();
        $data['published_at'] = ($data['is_published'] ?? false) ? now() : null;
        Announcement::create($data);
        return redirect()->route('annonces.index')->with('success','Annonce créée');
    }

    public function edit(Announcement $annonce) {
        return view('admin/annonces/form', compact('annonce'));
    }

    public function update(Request $request, Announcement $annonce) {
        $data = $request->validate([
            'title'=>'required|string|max:255',
            'content_html'=>'nullable|string',
            'media_type'=>'required|in:none,image,video',
            'media_url'=>'nullable|string|max:2048',
            'is_published'=>'sometimes|boolean',
            'is_pinned'=>'sometimes|boolean',
        ]);
        if (($data['is_published'] ?? false) && !$annonce->published_at) {
            $data['published_at'] = now();
        }
        $annonce->update($data);
        return back()->with('success','Annonce mise à jour');
    }

    public function destroy(Announcement $annonce) {
        $annonce->delete();
        return back()->with('success','Annonce supprimée');
    }

    public function toggle(Announcement $announcement) {
        $announcement->update([
            'is_published' => !$announcement->is_published,
            'published_at' => !$announcement->is_published ? now() : null
        ]);
        return back()->with('success','Statut publié modifié');
    }
}