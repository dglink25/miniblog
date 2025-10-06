@if($list->isEmpty())
  <p class="text-muted">Aucun élément.</p>
@else
  <div class="row g-3">
    @foreach($list as $article)
      <div class="col-md-6 col-lg-4">
        <div class="card h-100">
          @php $thumb = $article->media->firstWhere('type','image'); @endphp
          @if($thumb)
            <img class="card-img-top" src="{{ asset('storage/'.$thumb->file_path) }}" alt="">
          @elseif($article->image_path)
            <img class="card-img-top" src="{{ asset('storage/'.$article->image_path) }}" alt="">
          @endif
          <div class="card-body">
            <h5>{{ $article->title }}</h5>
            <div class="small text-muted">Statut : {{ $article->status }}</div>
            @if($article->status==='rejected' && $article->rejection_reason)
              <div class="text-danger small">Motif : {{ $article->rejection_reason }}</div>
            @endif
            <a class="btn btn-outline-primary btn-sm mt-2" href="{{ route('articles.show',$article) }}">Détails</a>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endif