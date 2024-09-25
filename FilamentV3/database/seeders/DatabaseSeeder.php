<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create(['name' => 'Admin', 'email' => 'admin@email.com', 'password' => Hash::make('123')]);

        // obs depois de criar o usuario acima tem que criar o usuario na unha no sistema
        // para depois fazer o relacionamento abaixo pois quando formata o banco o usuario
        // tenant_id fica nulo

        // Nao testado
        DB::table('tenants')->insert([
            'id' => 1,
            'code' => '2615cb70-080a-4cb6-9447-38edcecfca2d',
            'name' => 'tst',
            'slug' => 'tst',
        ]);

        DB::table('tenant_user')->insert([
            'tenant_id' => 1,
            'user_id' => 1,
        ]);

        \App\Models\User::factory(5)->create();
        \App\Models\Store::factory(5)->create();
        \App\Models\Category::factory(5)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
