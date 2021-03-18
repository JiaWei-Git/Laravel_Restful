<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Animal;
use App\Models\User;
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

        //Create data
        User::factory(5)->create();
        Animal::factory(2000)->create();
        Schema::enableForeignKeyConstraints();
    }
}
