@extends('layouts.app')
@section('content')
<div class="row g-3">
  <div class="col-lg-3">
    <div class="card h-100">
      <div class="card-header">Paramètres</div>
      <div class="card-body">
        <form action="{{ route('admin.toggleAutoPublish') }}" method="POST">@csrf
          <p>Publication automatique : <strong>{{ $settings->auto_publish ? 'Oui' : 'Non' }}</strong></p>
          <button class="btn btn-sm btn-outline-primary">Basculer</button>
        </form>
        <hr>
      </div>
    </div>
  </div>
  <div class="col-lg-9">
    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-pending">En attente</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-validated">Validés</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-rejected">Rejetés</button></li>
    </ul>
    <div class="tab-content pt-3">
      <div class="tab-pane fade show active" id="tab-pending">@include('admin.part-table',['items'=>$pending,'actions'=>true])</div>
      <div class="tab-pane fade" id="tab-validated">@include('admin.part-table',['items'=>$validated])</div>
      <div class="tab-pane fade" id="tab-rejected">@include('admin.part-table',['items'=>$rejected])</div>
    </div>
  </div>
</div>
@endsection