<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ApiFactory extends Factory
{
    protected $model = \App\Models\Api::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'base_url' => $this->faker->url(),
            'get' => '/get-endpoint',
            'put' => '/put-endpoint',
            'delete' => '/delete-endpoint',
        ];
    }
}
