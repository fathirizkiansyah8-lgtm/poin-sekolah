<?php

namespace Tests\Feature;

use App\Models\PointRule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PointRuleEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_page_displays_the_selected_point_rule_fields(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);
        $pointRule = PointRule::create([
            'code' => 'V-EDIT',
            'type' => 'violation',
            'category' => 'Disiplin',
            'name' => 'Terlambat masuk',
            'points' => 15,
            'description' => 'Contoh deskripsi aturan.',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('point-rules.edit', $pointRule))
            ->assertOk()
            ->assertSee('value="V-EDIT"', false)
            ->assertSee('value="violation" selected', false)
            ->assertSee('value="Disiplin"', false)
            ->assertSee('value="Terlambat masuk"', false)
            ->assertSee('value="15"', false)
            ->assertSee('Contoh deskripsi aturan.')
            ->assertSee('name="is_active"', false)
            ->assertDontSee('name="role"', false)
            ->assertDontSee('name="username"', false)
            ->assertDontSee('name="nip"', false);
    }
}
