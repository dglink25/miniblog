@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-body">
        <h1 class="h3 mb-3">{{ $settings->site_name ?? config('app.name') }}</h1>
        <div class="mb-3">{!! $settings->intro_html ?? '<p>Bienvenue !</p>' !!}</div>
        <form method="POST" action="{{ route('intro.accept') }}">@csrf
          <button class="btn btn-primary">J’ai compris</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
