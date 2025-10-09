<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Générer le sitemap du site Miniblog';

    public function handle()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/'))
            ->add(Url::create('/articles'))
            ->add(Url::create('/contact'))
            ->add(Url::create('/about'));

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('✅ Sitemap généré avec succès dans /public/sitemap.xml');
    }
}
