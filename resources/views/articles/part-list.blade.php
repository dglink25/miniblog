@if($list->isEmpty())
  <div class="empty-state">
    <div class="empty-icon">
      <i class="fas fa-inbox"></i>
    </div>
    <h3>Aucun élément trouvé</h3>
    <p class="text-muted">Aucun élément ne correspond à votre recherche pour le moment.</p>
  </div>
@else
  <div class="articles-grid">
    @foreach($list as $article)
      <div class="article-card">
        <!-- Image de l'article -->
        <div class="article-image">
          @php $thumb = $article->media->firstWhere('type','image'); @endphp
          @if($thumb)
            <img src="{{ $thumb->file_path }}" alt="{{ $article->title }}" class="article-img">
          @elseif($article->image_path)
            <img src="{{ $article->image_path }}" alt="{{ $article->title }}" class="article-img">
          @else
            <div class="article-placeholder">
              <i class="fas fa-image"></i>
              <span>Aucune image</span>
            </div>
          @endif
          
          <!-- Badge de statut -->
          <div class="status-badge status-{{ $article->status }}">
            {{ ucfirst($article->status) }}
          </div>
        </div>

        <!-- Contenu de la carte -->
        <div class="article-content">
          <h3 class="article-title">{{ $article->title }}</h3>
          
          <div class="article-meta">
            <span class="article-status">
              <i class="fas fa-circle status-{{ $article->status }}"></i>
              Statut : {{ $article->status }}
            </span>
            
            @if($article->status==='rejected' && $article->rejection_reason)
              <div class="rejection-reason">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Motif :</strong> {{ $article->rejection_reason }}
              </div>
            @endif
          </div>

          <div class="article-actions">
            <a href="{{ route('articles.show',$article) }}" class="btn-details">
              <i class="fas fa-eye"></i>
              Voir les détails
            </a>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endif

<style>
/* Grid responsive */
.articles-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 24px;
  padding: 20px 0;
}

/* Carte d'article */
.article-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  transition: all 0.3s ease;
  border: 1px solid #e9ecef;
}

.article-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Image */
.article-image {
  position: relative;
  height: 200px;
  overflow: hidden;
  background: #f8f9fa;
}

.article-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.article-card:hover .article-img {
  transform: scale(1.05);
}

.article-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  color: #6c757d;
  background: #f8f9fa;
}

.article-placeholder i {
  font-size: 3rem;
  margin-bottom: 8px;
  opacity: 0.5;
}

/* Badge de statut */
.status-badge {
  position: absolute;
  top: 12px;
  right: 12px;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.status-published { background: #d4edda; color: #155724; }
.status-draft { background: #fff3cd; color: #856404; }
.status-rejected { background: #f8d7da; color: #721c24; }
.status-pending { background: #cce7ff; color: #004085; }

/* Contenu */
.article-content {
  padding: 20px;
}

.article-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #2d3748;
  margin-bottom: 12px;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.article-meta {
  margin-bottom: 16px;
}

.article-status {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.875rem;
  color: #6c757d;
  margin-bottom: 8px;
}

.article-status i {
  font-size: 0.5rem;
}

.rejection-reason {
  background: #fff5f5;
  border: 1px solid #fed7d7;
  border-radius: 6px;
  padding: 10px;
  font-size: 0.8rem;
  color: #c53030;
  display: flex;
  align-items: flex-start;
  gap: 8px;
}

.rejection-reason i {
  margin-top: 2px;
  flex-shrink: 0;
}

/* Boutons */
.article-actions {
  display: flex;
  justify-content: flex-end;
}

.btn-details {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 10px 20px;
  background: #1e3a8a;
  color: white;
  text-decoration: none;
  border-radius: 6px;
  font-size: 0.875rem;
  font-weight: 500;
  transition: all 0.2s ease;
}

.btn-details:hover {
  background: #2563eb;
  color: white;
  transform: translateY(-1px);
}

/* État vide */
.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: #6c757d;
}

.empty-icon {
  font-size: 4rem;
  color: #dee2e6;
  margin-bottom: 20px;
}

.empty-state h3 {
  color: #495057;
  margin-bottom: 10px;
}

/* Responsive */
@media (max-width: 768px) {
  .articles-grid {
    grid-template-columns: 1fr;
    gap: 16px;
    padding: 16px 0;
  }
  
  .article-content {
    padding: 16px;
  }
  
  .article-title {
    font-size: 1.1rem;
  }
  
  .btn-details {
    width: 100%;
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .articles-grid {
    grid-template-columns: 1fr;
  }
  
  .article-image {
    height: 160px;
  }
}
</style>