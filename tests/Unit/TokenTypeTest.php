<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\TokenType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TokenTypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function token_type_can_be_created()
    {
        $tokenType = TokenType::factory()->create();

        $this->assertDatabaseHas('token_types', [
            'id' => $tokenType->id,
            'name' => $tokenType->name,
        ]);
    }
}
