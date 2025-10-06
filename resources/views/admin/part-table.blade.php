@if($items->isEmpty())
  <p class="text-muted">Rien à afficher.</p>
@else
  <div class="table-responsive">
  <table class="table align-middle">
    <thead><tr><th>Auteur</th><th>Titre</th><th>Statut</th><th>Actions</th></tr></thead>
    <tbody>
    @foreach($items as $a)
      <tr>
        <td>{{ $a->user->name }}</td>
        <td><a href="{{ route('articles.show',$a) }}">{{ $a->title }}</a></td>
        <td>{{ $a->status }}</td>
        <td>
          @if(($actions ?? false) && $a->status==='pending')
            <form action="{{ route('admin.articles.approve',$a) }}" method="POST" class="d-inline">@csrf
              <button class="btn btn-success btn-sm">Valider</button>
            </form>
            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rej{{ $a->id }}">Rejeter</button>
            <div class="modal fade" id="rej{{ $a->id }}" tabindex="-1">
              <div class="modal-dialog"><div class="modal-content">
                <form method="POST" action="{{ route('admin.articles.reject',$a) }}">@csrf
                  <div class="modal-header"><h5 class="modal-title">Motif de rejet</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                  <div class="modal-body"><textarea name="reason" class="form-control" required rows="4"></textarea></div>
                  <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button><button class="btn btn-danger">Rejeter</button></div>
                </form>
              </div></div>
            </div>
          @endif
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
  </div>
  {{ $items->links() }}
@endif