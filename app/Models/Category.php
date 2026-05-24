<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Tambahkan ini di atas

class Category extends Model
{
    protected $fillable = ['name', 'slug']; // Tambahkan 'slug' di sini

    // Fungsi sakti biar slug keisi otomatis sebelum data disimpan
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    public function events() {
        return $this->hasMany(Event::class);
    }
}