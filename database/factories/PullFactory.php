<?php

namespace Database\Factories;

use AngryMoustache\Media\Models\Attachment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PullFactory extends Factory
{
    public function definition()
    {
        $name = ucfirst($this->faker->words(rand(2, 5), true));
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'attachment_id' => Attachment::pluck('id')->random(),
            'pull_origin' => 'https://google.com',
            'source' => 'https://google.com',
            'online' => rand(0, 2) ? false : true,
            'created_at' => $this->faker->dateTimeBetween('-1 month'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', '-1 month'),
            'archived_at' => null,
        ];
    }
}
