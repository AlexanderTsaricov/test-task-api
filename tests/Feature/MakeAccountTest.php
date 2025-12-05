<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Company;
use App\Models\Account;

class MakeAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_account_for_company()
    {
        $company = Company::factory()->create();

        $this->artisan("make:account taxi {$company->id}")
            ->expectsOutput("Создан аккаунт taxi")
            ->assertExitCode(0);


        $this->assertDatabaseHas('accounts', [
            'name'       => 'taxi',
            'company_id' => $company->id,
        ]);
    }
}
