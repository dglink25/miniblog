@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-body">
        {!! $html !!}
        <div class="d-flex gap-2 mt-3">
          <form method="POST" action="{{ route('intro.skip') }}">@csrf<button class="btn btn-primary">Continuer</button></form>
          @auth <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Admin</a> @endauth
        </div>
      </div>
    </div>
  </div>
</div>
@endsection