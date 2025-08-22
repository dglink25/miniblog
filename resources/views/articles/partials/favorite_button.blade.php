<form method="POST" action="{{ route('favorites.toggle', $article) }}">
  @csrf
  <button class="btn btn-sm btn-outline-danger">
    {{ auth()->user()->favorites->contains($article->id) ? 'Retirer' : 'Favori' }}
  </button>
</form>