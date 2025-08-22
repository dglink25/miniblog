<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class SubscriptionController extends Controller
{
    public function __construct(){ $this->middleware('auth'); }

    public function follow(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) return back();
        auth()->user()->following()->syncWithoutDetaching([$user->id]);
        return back()->with('success',"Vous suivez désormais {$user->name}.");
    }
    public function unfollow(User $user): RedirectResponse
    {
        auth()->user()->following()->detach($user->id);
        return back()->with('success',"Abonnement retiré.");
    }
}