<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TagFactory extends Factory
{
    public function definition()
    {
        $name = ucfirst($this->faker->words(2, true));

        $parent = null;
        if (Tag::count() >= 10) {
            $parent = Tag::pluck('id')->random();
        }

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'parent_id' => $parent,
        ];
    }
}
