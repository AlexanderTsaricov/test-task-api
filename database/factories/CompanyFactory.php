<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    /**
     * Определение значений по умолчанию для модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->companyEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->optional()->streetAddress,
            'city' => $this->faker->optional()->city,
            'country' => $this->faker->optional()->country,
            'website' => $this->faker->optional()->url,
            'industry' => $this->faker->optional()->word,
            'tax_id' => $this->faker->optional()->numerify('#########'),
        ];
    }
}
