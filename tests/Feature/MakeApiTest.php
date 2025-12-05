<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Api;

class MakeApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_api_service()
    {
        $this->artisan('make:api testapi http://localhost --get="/items"')
            ->expectsOutput("Создан апи testapi")
            ->assertExitCode(0);

        $this->assertDatabaseHas('apis', [
            'name'     => 'testapi',
            'base_url' => 'http://localhost',
            'get'      => '/items',
        ]);
    }
}
