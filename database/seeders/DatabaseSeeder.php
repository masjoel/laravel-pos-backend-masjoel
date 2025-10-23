<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\ProfilUsaha;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        // User::factory(10)->create();

        
        // User::factory()->create([
        //     'name' => 'Owner',
        //     'email' => 'owner@tokopojok.com',
        //     'password' => Hash::make('password'),
        //     'roles' => 'reseller',
        //     'reseller_id' => 'A01',
        // ]);
        ProfilUsaha::create([
            'nama_client' => 'Toko Pojok',
            'kodedesa' => '00000000',
            'provinsi' => 'Jawa Tengah',
            'kabupaten' => 'Kabupaten',
            'kecamatan' => 'Kecamatan',
            'alamat_client' => 'Jl. Raya No.1',
            'kades' => 'Direktur',
            'sekretaris' => 'Sekretaris',
            'bendahara' => 'Bendahara',
            'logo' => 'image/icon-lkp2mpd.png',
            'photo' => 'image/icon-foto.png',
            'apikey' => Uuid::uuid1()->getHex(),
        ]);


        // $this->call([
            // ProductSeeder::class,
        // ]);
    }
}
