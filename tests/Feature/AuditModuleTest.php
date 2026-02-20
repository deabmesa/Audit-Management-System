<?php

namespace Tests\Feature;

use App\Models\Audit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_audit(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->post(route('audits.store'), [
                'title' => 'Finance Controls',
                'business_unit' => 'Finance',
                'audit_owner' => 'Jane Doe',
                'audit_date' => now()->toDateString(),
                'status' => 'Planned',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('audits', ['title' => 'Finance Controls']);
    }

    public function test_dashboard_is_available_for_authenticated_users(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk();
    }
}
