<?php

namespace Tests\Feature;

use App\Models\Audit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssueModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_issue_can_be_created_for_an_audit(): void
    {
        $user = User::factory()->create();
        $audit = Audit::factory()->create();

        $this->actingAs($user)
            ->post(route('audits.issues.store', $audit), [
                'title' => 'Missing Approval Workflow',
                'description' => 'Approval is bypassed',
                'risk_level' => 'High',
                'recommendation' => 'Implement dual control',
                'due_date' => now()->addDays(30)->toDateString(),
                'responsible_person' => 'Ops Lead',
                'status' => 'Open',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('issues', ['title' => 'Missing Approval Workflow']);
    }
}
