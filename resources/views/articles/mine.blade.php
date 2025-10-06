@extends('layouts.app')
@section('content')
<h1 class="h3 mb-3">Mes publications</h1>
<ul class="nav nav-tabs" role="tablist">
  <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pending">En attente</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#validated">Validés</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#rejected">Rejetés</button></li>
</ul>
<div class="tab-content pt-3">
  <div class="tab-pane fade show active" id="pending">@include('articles.part-list',['list'=>$pending])</div>
  <div class="tab-pane fade" id="validated">@include('articles.part-list',['list'=>$validated])</div>
  <div class="tab-pane fade" id="rejected">@include('articles.part-list',['list'=>$rejected,true])</div>
</div>
@endsection