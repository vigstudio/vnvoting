<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminCanAccessCountingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_counting_dashboard(): void
    {
        // Setup Role
        Role::firstOrCreate(['name' => 'admin']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/counting');

        $response->assertStatus(200);
    }
}
