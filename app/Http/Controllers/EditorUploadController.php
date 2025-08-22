<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; use Illuminate\Support\Facades\Storage;
class EditorUploadController extends Controller {
  public function store(Request $r){
    $r->validate(['file'=>'required|image|mimes:jpg,jpeg,png,webp,gif|max:4096']);
    $path = $r->file('file')->store('editor','public');
    return response()->json(['location' => asset('storage/'.$path)]);
  }
}