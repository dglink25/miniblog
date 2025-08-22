@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-md-8 col-lg-6">
    <h1 class="h4 mb-3">Envoyer une suggestion</h1>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('suggestions.store') }}" class="card shadow-sm">
      @csrf
      <div class="card-body">
        <div class="mb-3">
          <label class="form-label">Votre suggestion</label>
          <textarea name="message" rows="6" class="form-control" minlength="10" required>{{ old('message') }}</textarea>
        </div>
        <button class="btn btn-primary">Envoyer</button>
      </div>
    </form>
  </div>
</div>
@endsection
