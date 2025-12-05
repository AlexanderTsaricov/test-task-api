<?php

namespace Database\Factories;

use App\Models\Token;
use App\Models\Account;
use App\Models\Api;
use App\Models\TokenType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TokenFactory extends Factory
{
    protected $model = Token::class;

    public function definition()
    {
        return [
            'account_id' => Account::factory(),
            'api_id' => Api::factory(),
            'token_type_id' => TokenType::factory(),
            'token' => $this->faker->sha256,
            'expires_at' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
        ];
    }
}
