<?php

namespace Tests\Feature;

use App\Models\LaboratoryAnalysis;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaboratoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PermissionSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_medecin_can_create_lab_request(): void
    {
        $medecin = User::factory()->create(['must_change_password' => false]);
        $medecin->assignRole('Médecin');

        $patient = Patient::factory()->create();

        $response = $this->actingAs($medecin)->post(route('laboratory.store'), [
            'patient_id' => $patient->id,
            'analysis_type' => 'Hémogramme (NFS)',
            'description' => 'Bilan de routine',
        ]);

        $response->assertRedirect(route('laboratory.index'));
        $this->assertDatabaseHas('laboratory_analyses', [
            'patient_id' => $patient->id,
            'analysis_type' => 'Hémogramme (NFS)',
            'status' => 'demandee',
            'requested_by' => $medecin->id,
        ]);
    }

    public function test_laborantin_can_enter_results(): void
    {
        $laborantin = User::factory()->create(['must_change_password' => false]);
        $laborantin->assignRole('Laborantin');

        $patient = Patient::factory()->create();
        $medecin = User::factory()->create(['must_change_password' => false]);
        $medecin->assignRole('Médecin');

        $analysis = LaboratoryAnalysis::create([
            'patient_id' => $patient->id,
            'requested_by' => $medecin->id,
            'analysis_type' => 'Glycémie',
            'status' => 'demandee',
            'requested_at' => now(),
        ]);

        $response = $this->actingAs($laborantin)->put(route('laboratory.update', $analysis), [
            'results' => 'Glycémie: 0.95 g/L (normal)',
        ]);

        $response->assertRedirect(route('laboratory.show', $analysis));
        $analysis->refresh();
        $this->assertEquals('terminee', $analysis->status);
        $this->assertNotNull($analysis->completed_at);
    }

    public function test_laborantin_can_validate_results(): void
    {
        $laborantin = User::factory()->create(['must_change_password' => false]);
        $laborantin->assignRole('Laborantin');

        $patient = Patient::factory()->create();
        $medecin = User::factory()->create(['must_change_password' => false]);
        $medecin->assignRole('Médecin');

        $analysis = LaboratoryAnalysis::create([
            'patient_id' => $patient->id,
            'requested_by' => $medecin->id,
            'analysis_type' => 'Cholestérol',
            'status' => 'terminee',
            'results' => 'Résultats normaux',
            'requested_at' => now()->subDays(2),
            'completed_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($laborantin)->put(route('laboratory.validate', $analysis));

        $response->assertRedirect(route('laboratory.show', $analysis));
        $analysis->refresh();
        $this->assertEquals('validee', $analysis->status);
        $this->assertEquals($laborantin->id, $analysis->validated_by);
    }
}
