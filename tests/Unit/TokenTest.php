<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Company;
use App\Models\Account;
use App\Models\Api;
use App\Models\TokenType;
use App\Models\Token;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TokenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function token_can_be_created(): void
    {
        $company = Company::factory()->create();
        $account = Account::factory()->create(['company_id' => $company->id]);
        $api = Api::factory()->create();
        $tokenType = TokenType::factory()->create();

        $token = Token::factory()->create([
            'account_id' => $account->id,
            'api_id' => $api->id,
            'token_type_id' => $tokenType->id,
        ]);

        $this->assertDatabaseHas('tokens', [
            'id' => $token->id,
            'account_id' => $account->id,
            'api_id' => $api->id,
            'token_type_id' => $tokenType->id,
        ]);
    }
}
