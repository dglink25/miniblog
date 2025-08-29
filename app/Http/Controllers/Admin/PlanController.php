<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller{
    public function index(){ $plan=Plan::latest()->get(); return view('admin/plans/index', compact('plan')); }
    public function create(){ return view('admin/plans/form', ['plan'=>new Plan()]); }
    public function store(Request $r){
        $data = $r->validate([
            'name'=>'required|string|max:100',
            'duration_days'=>'required|integer|min:1',
            'price'=>'required|integer|min:0',
            'is_active'=>'sometimes|boolean'
        ]);
        Plan::create($data + ['is_active'=>$r->boolean('is_active')]);
        return redirect()->route('plans.index')->with('success','Plan créé');
    }
    public function edit(Plan $plan){ return view('admin/plans/form', compact('plan')); }
    public function update(Request $r, Plan $plan){
        $data = $r->validate([
            'name'=>'required|string|max:100',
            'duration_days'=>'required|integer|min:1',
            'price'=>'required|integer|min:0',
            'is_active'=>'sometimes|boolean'
        ]);
        $plan->update($data + ['is_active'=>$r->boolean('is_active')]);
        return back()->with('success','Plan mis à jour');
    }
    public function destroy(Plan $plan){ $plan->delete(); return back()->with('success','Plan supprimé'); }
}