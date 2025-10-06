<?php 
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionAdminController extends Controller
{
    public function index(){
        $items = Suggestion::latest()->paginate(20);
        return view('admin/suggestions/index', compact('items'));
    }
    public function show(Suggestion $suggestion){
        return view('admin/suggestions/show', compact('suggestion'));
    }
    public function updateStatus(Request $r, Suggestion $suggestion){
        $r->validate(['status'=>'required|in:new,seen,closed']);
        $suggestion->update(['status'=>$r->status]);
        return back()->with('success','Statut mis à jour');
    }
}