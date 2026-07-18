<?php
// app/Models/Organizer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Organizer extends Model
{
    protected $fillable = [
        'user_id', 'organization_name', 'slug', 'description', 'logo_path', 'status', 'verified_at',
    ];

    protected function casts(): array
    {
        return ['verified_at' => 'datetime'];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($organizer) {
            $organizer->slug = Str::slug($organizer->organization_name) . '-' . Str::random(4);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    // Semua review dari semua event milik organizer ini
    public function reviews()
    {
        return Review::whereIn('event_id', $this->events()->pluck('id'));
    }

    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function totalReviews(): int
    {
        return $this->reviews()->count();
    }
}