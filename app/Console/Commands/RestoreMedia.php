<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RestoreMedia extends Command{
    protected $signature = 'media:restore';
    protected $description = 'Restaurer les médias depuis storage/app/media vers public';

    public function handle()
    {
        $source = storage_path('app/media'); // stockage persistant
        $dest = public_path('uploads'); // dossier utilisé dans les vues

        if (!File::exists($source)) {
            $this->info('Pas de fichiers à restaurer.');
            return;
        }

        // Supprimer le dossier public actuel pour éviter les doublons
        if (File::exists($dest)) {
            File::deleteDirectory($dest);
        }

        File::copyDirectory($source, $dest);
        $this->info('Tous les fichiers ont été restaurés dans public/uploads');
    }
}
