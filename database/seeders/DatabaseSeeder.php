<?php

namespace Database\Seeders;

use App\Models\Pull;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\Tag::factory(10)->create();
        // \App\Models\Tag::factory(20)->create();
        // \App\Models\Tag::factory(30)->create();
        // \App\Models\Pull::factory(100)->create();

        // $tags = Tag::pluck('id');
        // foreach (Pull::withoutGlobalScopes()->get() as $pull) {
        //     for ($i = 0; $i < rand(2, 6); $i++) {
        //         $pull->tags()->attach($tags->random());
        //     }
        // }
    }
}
