<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Event;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Utama
        User::create([
            'name' => 'Admin Amikom',
            'email' => 'admin@amikom.ac.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // 2. Insert Kategori (3 Kategori)
        $catTechnology = Category::create([
            'name' => 'Technology',
            'slug' => 'technology',
        ]);

        $catCreative = Category::create([
            'name' => 'Creative & Design',
            'slug' => 'creative-design',
        ]);

        $catSport = Category::create([
            'name' => 'E-Sport & Hobby',
            'slug' => 'e-sport-hobby',
        ]);

        // 3. Insert Sampel Events (6 Event Bervariasi)
        
        // Event 1 - Technology
        Event::create([
            'category_id' => $catTechnology->id,
            'title' => 'AI & Future Tech Summit 2026',
            'description' => 'Jelajahi tren terkini dalam kecerdasan buatan dan teknologi masa depan bersama para ahli di bidangnya.',
            'date' => '2026-05-01 13:00:00',
            'location' => 'Cinema Unit 6',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-1.png',
        ]);

        // Event 2 - Technology
        Event::create([
            'category_id' => $catTechnology->id,
            'title' => 'Hackaton - Unleash Your Inner Developer',
            'description' => 'Ayo asah skill coding kamu dan ciptakan solusi inovatif untuk tantangan masa depan!',
            'date' => '2026-05-15 09:00:00',
            'location' => 'Inkubator Amikom',
            'price' => 75000,
            'stock' => 50,
            'poster_path' => 'posters/event-2.png',
        ]);

        // Event 3 - Creative
        Event::create([
            'category_id' => $catCreative->id,
            'title' => 'UI/UX Masterclass: From Zero to Hero',
            'description' => 'Pelajari prinsip desain antarmuka dan pengalaman pengguna yang modern dan aplikatif.',
            'date' => '2026-06-10 10:00:00',
            'location' => 'Lab Multimedia',
            'price' => 120000,
            'stock' => 30,
            'poster_path' => 'posters/event-3.png',
        ]);

        // Event 4 - Creative
        Event::create([
            'category_id' => $catCreative->id,
            'title' => 'Digital Illustration Workshop',
            'description' => 'Workshop intensif menggambar digital menggunakan software standar industri.',
            'date' => '2026-06-20 13:00:00',
            'location' => 'Ruang Seminar',
            'price' => 45000,
            'stock' => 40,
            'poster_path' => 'posters/event-4.png',
        ]);

        // Event 5 - Sport
        Event::create([
            'category_id' => $catSport->id,
            'title' => 'E-Sport U-Champ: Valorant Tournament',
            'description' => 'Tunjukkan skill tim kamu dalam turnamen Valorant bergengsi antar mahasiswa!',
            'date' => '2026-07-05 08:00:00',
            'location' => 'Student Hall',
            'price' => 25000,
            'stock' => 128,
            'poster_path' => 'posters/event-5.png',
        ]);

        // Event 6 - Sport
        Event::create([
            'category_id' => $catSport->id,
            'title' => 'Mobile Legends National Series',
            'description' => 'Kompetisi Mobile Legends tingkat regional dengan total hadiah jutaan rupiah.',
            'date' => '2026-07-12 10:00:00',
            'location' => 'Amikom Baru',
            'price' => 30000,
            'stock' => 200,
            'poster_path' => 'posters/event-6.png',
        ]);
    }
}