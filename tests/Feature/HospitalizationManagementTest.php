<?php

namespace Tests\Feature;

use App\Models\Hospitalization;
use App\Models\Bed;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HospitalizationManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PermissionSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_admin_can_view_hospitalizations_index(): void
    {
        $admin = User::factory()->create(['must_change_password' => false]);
        $admin->assignRole('Directeur Général Médecin');

        $response = $this->actingAs($admin)->get(route('hospitalizations.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_hospitalization(): void
    {
        $admin = User::factory()->create(['must_change_password' => false]);
        $admin->assignRole('Directeur Général Médecin');

        $patient = Patient::factory()->create();
        $bed = Bed::factory()->create(['is_available' => true]);

        $response = $this->actingAs($admin)->post(route('hospitalizations.store'), [
            'patient_id' => $patient->id,
            'bed_id' => $bed->id,
            'admission_date' => now()->format('Y-m-d H:i:s'),
            'expected_duration' => 5,
            'admission_reason' => 'Test admission',
        ]);

        $response->assertRedirect(route('hospitalizations.index'));
        $this->assertDatabaseHas('hospitalizations', ['patient_id' => $patient->id, 'status' => 'active']);

        $bed->refresh();
        $this->assertFalse($bed->is_available);
    }

    public function test_discharge_sets_bed_available(): void
    {
        $admin = User::factory()->create(['must_change_password' => false]);
        $admin->assignRole('Directeur Général Médecin');

        $patient = Patient::factory()->create();
        $bed = Bed::factory()->create(['is_available' => false]);
        $hospitalization = Hospitalization::factory()->create([
            'patient_id' => $patient->id,
            'bed_id' => $bed->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->put(route('hospitalizations.discharge', $hospitalization), [
            'discharge_date' => now()->format('Y-m-d H:i:s'),
            'discharge_notes' => 'Test discharge',
        ]);

        $response->assertRedirect(route('hospitalizations.show', $hospitalization));
        $hospitalization->refresh();
        $this->assertEquals('discharged', $hospitalization->status);

        $bed->refresh();
        $this->assertTrue($bed->is_available);
    }
}
