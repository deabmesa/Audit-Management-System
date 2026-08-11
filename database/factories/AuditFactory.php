<?php

namespace Database\Factories;

use App\Models\Audit;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditFactory extends Factory
{
    protected $model = Audit::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'business_unit' => fake()->company(),
            'audit_owner' => fake()->name(),
            'audit_date' => now()->toDateString(),
            'status' => 'Planned',
        ];
    }
}
