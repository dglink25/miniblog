<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function followers() {
        return $this->belongsToMany(User::class, 'subscriptions', 'followed_id', 'follower_id');
    }
    public function following() {
        return $this->belongsToMany(User::class, 'subscriptions', 'follower_id', 'followed_id');
    }
    public function articles() {
        return $this->hasMany(\App\Models\Article::class);
    }
    public function articleRatings() {
        return $this->hasMany(\App\Models\ArticleRating::class);
    }

    public function rating() { return $this->hasOne(\App\Models\Rating::class); }
    public function suggestions() { return $this->hasMany(\App\Models\Suggestion::class); }

    //public function articles() { return $this->hasMany(\App\Models\Article::class); }
    public function comments() { return $this->hasMany(\App\Models\Comment::class); }

    // app/Models/User.php
    public function favorites(){
        return $this->belongsToMany(Article::class, 'favorites')->withTimestamps();
    }

    // app/Models/Article.php
    public function favoritedBy(){
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
    // App\Models\User.php
    public function isAdmin()
    {
        return $this->is_admin; // true si admin
    }
    public function subscription(){ return $this->hasMany(\App\Models\Subscription::class); }

    public function subscriptions(){
        return $this->hasMany(\App\Models\Subscription::class);
    }

    public function activeSubscription(){
        return $this->subscriptions()
            ->where('status','active')
            ->where('ends_at','>', now())
            ->latest('ends_at')->first();
    }

    public function canPublish(): bool {
        return (bool) $this->activeSubscription();
    }

    public function dismissedAnnouncements(){
        return $this->belongsToMany(Announcement::class, 'announcements')
            ->withTimestamps();
    }



}
