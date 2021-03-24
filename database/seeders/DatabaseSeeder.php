<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Animal;
use App\Models\User;
use App\Models\Type;
use Illuminate\Support\Facades\Schema;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Clear db
        Schema::disableForeignKeyConstraints();
        Animal::truncate();
        User::truncate();
        Type::truncate();
        //Create data
        Type::factory(5)->create();
        User::factory(5)->create();
        Animal::factory(2000)->create();
        Schema::enableForeignKeyConstraints();
    }
}
