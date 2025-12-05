<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;

class AccountFactory extends Factory
{
    protected $model = \App\Models\Account::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Account',
            'company_id' => Company::factory(),
        ];
    }
}
