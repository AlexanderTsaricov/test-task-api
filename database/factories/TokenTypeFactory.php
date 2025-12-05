<?php

namespace Database\Factories;

use App\Models\TokenType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TokenTypeFactory extends Factory
{
    protected $model = TokenType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
        ];
    }
}
