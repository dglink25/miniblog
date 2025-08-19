<?php

namespace App\Providers;
use App\Models\Article;
use App\Policies\ArticlePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    protected $policies = [
        Article::class => ArticlePolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
