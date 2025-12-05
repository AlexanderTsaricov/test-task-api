<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\TokenType;

class MakeTokenTypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_token_type()
    {
        $this->artisan('make:token-type "api token"')
            ->expectsOutput("Создан тип токенов api token")
            ->assertExitCode(0);

        $this->assertDatabaseHas('token_types', [
            'name' => 'api token',
        ]);
    }
}
