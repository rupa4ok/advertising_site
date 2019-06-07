<?php

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Region::class, 10)->create()->each(function (Region $region) {
            $region->children()->saveMany(factory(Region::class, random_int(3, 10))->create()->each(function (Region $region) {
                $region->children()->saveMany(factory(Region::class, random_int(3, 10))->make());
            }));
        });
    }
}
