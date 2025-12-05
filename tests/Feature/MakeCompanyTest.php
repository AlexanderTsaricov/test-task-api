<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Company;

class MakeCompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_company()
    {
        $this->artisan("make:company 'ООО Ромашка' info@romashka.ru '+7 999 123-45-67'")
            ->expectsOutput("Компания 'ООО Ромашка' успешно добавлена.")
            ->assertExitCode(0);

        $this->assertDatabaseHas('companies', [
            'name'  => 'ООО Ромашка',
            'email' => 'info@romashka.ru',
            'phone' => '+7 999 123-45-67',
        ]);
    }
}
