<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function account_can_be_created(): void
    {
        $account = Account::factory()->create();

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'name' => $account->name,
        ]);
    }
}
