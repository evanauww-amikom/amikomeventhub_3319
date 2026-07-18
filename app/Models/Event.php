<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'location',
        'price',
        'stock',
        'category_id',
        'organizer_id',
        'poster_path',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Sudah lewat waktunya? dipakai buat validasi boleh-nggaknya review
    public function hasEnded(): bool
    {
        return $this->date < now();
    }
}