<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Company;
use App\Models\Account;
use App\Models\Api;
use App\Models\TokenType;
use App\Models\Token;

class MakeTokenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_token()
    {
        $company    = Company::factory()->create();
        $account    = Account::factory()->create(['company_id' => $company->id]);
        $api        = Api::factory()->create();
        $tokenType  = TokenType::factory()->create();

        $this->artisan("make:token {$account->id} {$api->id} {$tokenType->id} abc123 --expires_at='2025-12-31 23:59:59'")
            ->expectsOutput("Создан токен 'abc123' для account_id={$account->id}, api_id={$api->id}, type_id={$tokenType->id}")
            ->assertExitCode(0);

        $this->assertDatabaseHas('tokens', [
            'account_id'    => $account->id,
            'api_id'        => $api->id,
            'token_type_id' => $tokenType->id,
            'token'         => 'abc123',
        ]);
    }
}
