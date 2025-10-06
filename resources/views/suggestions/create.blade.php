{{-- resources/views/suggestions/create.blade.php --}}
@extends('layouts.app')
@section('content')
<h1 class="h4 mb-3">Votre suggestion</h1>
<form method="POST" action="{{ route('suggestions.store') }}">
  @csrf
  <div class="mb-3"><label class="form-label">Objet</label>
    <input class="form-control" name="subject" value="{{ old('subject') }}" required>
  </div>
  <div class="mb-3"><label class="form-label">Message</label>
    <textarea class="form-control" name="message" rows="6" required>{{ old('message') }}</textarea>
  </div>
  <button class="btn btn-primary">Envoyer</button>
</form>
@endsection