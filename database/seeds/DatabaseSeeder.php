<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        factory(User::class, 5000)->create();
        $this->call(RegionsTableSeeder::class);
        $this->call(AdvertCategoriesTableSeeder::class);
    }
}
