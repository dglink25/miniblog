<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller{
    public function __construct(){ $this->middleware(['auth','can:admin']); }

    public function index(){
        $plans = Plan::orderBy('price')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create(){
        return view('admin.plans.form', ['plan'=>new Plan()]);
    }

    public function store(Request $r){
        $data = $r->validate([
            'name'=>'required|string|max:100',
            'duration_days'=>'required|integer|min:1',
            'price'=>'required|integer|min:0',
            'payment_provider'=>'required|in:kia,feda,other',
            'payment_link'=>'nullable|url',
            'is_active'=>'sometimes|boolean'
        ]);
        Plan::create($data + ['is_active'=>$r->boolean('is_active')]);
        return redirect()->route('admin.plans.index')->with('success','Plan créé');
    }

    public function edit(Plan $plan){
        return view('admin.plans.form', compact('plan'));
    }

    public function update(Request $r, Plan $plan){
        $data = $r->validate([
            'name'=>'required|string|max:100',
            'duration_days'=>'required|integer|min:1',
            'price'=>'required|integer|min:0',
            'payment_provider'=>'required|in:kia,feda,other',
            'payment_link'=>'nullable|url',
            'is_active'=>'sometimes|boolean'
        ]);
        $plan->update($data + ['is_active'=>$r->boolean('is_active')]);
        return redirect()->route('admin.plans.index')->with('success','Plan mis à jour');
    }

    public function destroy(Plan $plan){
        $plan->delete();
        return back()->with('success','Plan supprimé');
    }
}
