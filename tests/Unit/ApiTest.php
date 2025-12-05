<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Api;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function api_can_be_created(): void
    {
        $api = Api::factory()->create();

        $this->assertDatabaseHas('apis', [
            'id' => $api->id,
            'name' => $api->name,
            'base_url' => $api->base_url,
        ]);
    }
}
