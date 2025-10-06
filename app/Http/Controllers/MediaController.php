<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;   // <-- Import Storage
use App\Models\Media;                     // <-- Import ton modèle Media

class MediaController extends Controller
{
    public function destroy(Media $media)
    {
        // Vérifie les permissions via ta Policy
        $this->authorize('delete', $media->article);

        // Supprime le fichier du disque
        Storage::disk('public')->delete($media->file_path);

        // Supprime l’enregistrement en base
        $media->delete();

        return back()->with('success', 'Fichier supprimé.');
    }
}
