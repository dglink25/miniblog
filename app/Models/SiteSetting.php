<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $guarded = [];
    public static function current(): self
    {
        return static::withoutGlobalScopes()->inRandomOrder()->limit(1)->first()
            ?? static::create([
                'company_name' => 'DGLINK',
                'site_name' => 'MiniBlog',
                'auto_publish' => false,
                'intro_enabled' => true,
                'intro_html' => '<h1>Bienvenue</h1><p>Découvrez notre plateforme.</p>',
            ]);
    }

}
