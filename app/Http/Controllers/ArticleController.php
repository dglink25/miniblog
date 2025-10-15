<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SiteSetting;
use Purifier;
use App\Models\Announcement;

use Cloudinary\Cloudinary;         // SDK principal
use Cloudinary\Api\Upload\UploadApi; // API upload


class ArticleController extends Controller{
    public function __construct(){
        // Accès public pour index/show ; reste protégé
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request){
        $file = $request->file('image');
        if ($file) {
            $filename = time().'_'.$file->getClientOriginalName();

            // Copier dans storage persistant
            $file->storeAs('media', $filename);

            // Copier dans public pour affichage immédiat
            copy(storage_path('app/media/'.$filename), public_path('uploads/'.$filename));

            // Sauvegarder le nom dans la base
            $article->image = $filename;
        }

        $q = $request->string('q')->toString();

        $articles = Article::with('user')
            ->when($q, fn($query) => $query->where(function($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%");
            }))
            ->where('status', 'validated')
            ->orderByDesc('pinned')      // 🔹 les articles épinglés en premier
            ->latest('published_at')     // puis par date de publication
            ->paginate(5)
            ->withQueryString();

        $annonces = Announcement::published()
            ->when(auth()->check(), function($q) {
                $q->whereNotIn('id', function($sub){
                    $sub->select('announcement_id')
                        ->from('announcement_user_dismissals')
                        ->where('user_id', auth()->id());
                });
            })
            ->orderByDesc('is_pinned') // épinglés en tête
            ->orderByDesc('published_at')
            ->take(3) // limite si tu veux
            ->get();

        return view('articles.index', compact('articles','q','annonces'));
    }

    public function create(){
        return view('articles.create');
    }

    

    public function store(StoreArticleRequest $request): RedirectResponse{
        $validated = $request->validated();

        $path = null;

        if ($request->hasFile('image')) {
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
            $uploadApi = new UploadApi();

            $uploaded = $uploadApi->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'articles']
            );

            $path = $uploaded['secure_url'];
        }

        $data['content'] = \Purifier::clean($request->input('content'));
        $settings = \App\Models\SiteSetting::current();
        $auto = (bool) $settings->auto_publish;

        $article = Article::create([
            'user_id'       => auth()->id(),
            'title'         => $validated['title'],
            'content'       => $data['content'],
            'image_path'    => $path,
            'status'        => $auto ? 'validated' : 'pending',
            'published_at'  => $auto ? now() : null,
            'is_published'  => $auto,
        ]);

        // Médias multiples
        if ($request->hasFile('media')) {
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
            $uploadApi = new UploadApi();

            foreach ($request->file('media') as $file) {
                $mime = $file->getMimeType();
                $folder = 'articles/media';

                if (str_starts_with($mime, 'video')) {
                    // 🔹 Upload vidéo en mode "large"
                    $uploaded = $uploadApi->upload(
                    $file->getRealPath(),
                    [
                        'folder' => $folder,
                        'resource_type' => 'video',
                        'chunk_size' => 6000000, // 6 Mo
                    ]
                );

                } else {
                    // 🔹 Upload image / fichier normal
                    $uploaded = $uploadApi->upload(
                        $file->getRealPath(),
                        ['folder' => $folder]
                    );
                }

                $type = str_starts_with($mime, 'image') ? 'image'
                    : (str_starts_with($mime, 'video') ? 'video' : 'file');

                $article->media()->create([
                    'file_path' => $uploaded['secure_url'],
                    'mime_type' => $mime,
                    'type' => $type,
                ]);
            }
        }


        return redirect()->route('articles.mine')
            ->with('success', 'Article enregistré avec succès !');
    }

    public function show(Article $article){

        /* Exemple upload article
        $file = $article->image;
        if ($file) {
            $filename = time().'_'.$file->getClientOriginalName();

            // Copier dans storage persistant
            $file->storeAs('media', $filename);

            // Copier dans public pour affichage immédiat
            copy(storage_path('app/media/'.$filename), public_path('uploads/'.$filename));

            // Sauvegarder le nom dans la base
            $article->image = $filename;
        }
*/
        
        $article->load(['user','comments.user']);
        return view('articles.show', compact('article'));
    }
    //show

    public function edit(Article $article)
{
    $this->authorize('update', $article);
    return view('articles.edit', compact('article'));
}

    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse{
        $this->authorize('update', $article);

        $data = $request->only('title','content');
        $uploadApi = new UploadApi();

        // 🔹 Image principale
        if ($request->hasFile('image')) {
            if ($article->image_path) {
                $publicId = pathinfo($article->image_path, PATHINFO_FILENAME);
                $uploadApi->destroy("articles/{$publicId}");
            }

            $uploaded = $uploadApi->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'articles']
            );
            $data['image_path'] = $uploaded['secure_url'];
        }

        // 🔹 Médias multiples (mise à jour)
        if ($request->hasFile('media')) {
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
            $uploadApi = new UploadApi();

            foreach ($request->file('media') as $file) {
                $mime = $file->getMimeType();
                $folder = 'articles/media';

                // 🔸 Options de base
                $options = ['folder' => $folder];

                // 🔹 Si c’est une vidéo, on ajoute le type et la gestion des gros fichiers
                if (str_starts_with($mime, 'video')) {
                    $options['resource_type'] = 'video';
                    $options['chunk_size'] = 6000000; // 6 Mo
                }

                // 🔹 Upload vers Cloudinary
                $uploaded = $uploadApi->upload(
                    $file->getRealPath(),
                    $options
                );

                // 🔹 Détermination du type de média
                $type = str_starts_with($mime, 'image')
                    ? 'image'
                    : (str_starts_with($mime, 'video') ? 'video' : 'file');

                // 🔹 Enregistrement en base
                $article->media()->create([
                    'file_path' => $uploaded['secure_url'],
                    'mime_type' => $mime,
                    'type' => $type,
                ]);
            }
        }


        $article->update($data);

        return redirect()->route('articles.show', $article)
            ->with('success', "L'article a été mis à jour.");
    }

    public function destroy(Article $article): RedirectResponse{
        $this->authorize('delete', $article);

        $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
        $uploadApi = new UploadApi();

        // 🔹 Supprimer les médias associés
        foreach ($article->media as $m) {
            $url = $m->file_path;
            $mime = $m->mime_type;

            // Extraire le dossier + nom de fichier sans extension
            $pathParts = pathinfo(parse_url($url, PHP_URL_PATH));
            $publicId = 'articles/media/' . $pathParts['filename'];

            // Déterminer le type de ressource (image ou vidéo)
            $resourceType = str_starts_with($mime, 'video') ? 'video' : 'image';

            // Suppression sur Cloudinary
            $uploadApi->destroy($publicId, ['resource_type' => $resourceType]);
        }

        // 🔹 Supprimer l'image principale
        if ($article->image_path) {
            $pathParts = pathinfo(parse_url($article->image_path, PHP_URL_PATH));
            $publicId = 'articles/' . $pathParts['filename'];

            $uploadApi->destroy($publicId, ['resource_type' => 'image']);
        }

        // 🔹 Supprimer l'article en base
        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', "L'article et tous ses fichiers Cloudinary ont été supprimés.");
    }



    public function mine(){
        $userId = auth()->id();
        $pending = \App\Models\Article::with('user','media')->where('user_id',$userId)->pending()->latest()->get();
        $validated = \App\Models\Article::with('user','media')->where('user_id',$userId)->published()->latest('published_at')->get();
        $rejected = \App\Models\Article::with('user','media')->where('user_id',$userId)->rejected()->latest()->get();
        return view('articles.mine', compact('pending','validated','rejected'));
    }

    // app/Http/Controllers/ArticleController.php
    public function byUser(\App\Models\User $user){

        // Exemple upload article
        $file = $request->file('image');
        $filename = time().'_'.$file->getClientOriginalName();

        // Copier dans storage persistant
        $file->storeAs('media', $filename);

        // Copier dans public pour affichage immédiat
        copy(storage_path('app/media/'.$filename), public_path('uploads/'.$filename));

        // Sauvegarder le nom dans la base
        $article->image = $filename;
        $article->save();

        $articles = $user->articles()
            ->with(['media','user'])
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(9);
        return view('users.articles', compact('user','articles'));
    }
    
    public function react(Request $request, Article $article){
        $request->validate(['type' => 'required|in:like,dislike']);

        $article->reactions()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['type' => $request->type]
        );

        return back()->with('success', "Réaction enregistrée !");
    }

}
