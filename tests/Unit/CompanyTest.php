<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function company_can_be_created()
    {
        $company = Company::factory()->create([
            'name' => 'Test Company',
        ]);

        $this->assertDatabaseHas('companies', [
            'name' => 'Test Company',
        ]);
    }
}
