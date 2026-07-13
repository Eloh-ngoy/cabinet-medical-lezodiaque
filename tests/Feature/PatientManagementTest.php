<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PermissionSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_admin_can_view_patients_index(): void
    {
        $admin = User::factory()->create(['must_change_password' => false]);
        $admin->assignRole('Directeur Général Médecin');

        $response = $this->actingAs($admin)->get(route('patients.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_patient(): void
    {
        $admin = User::factory()->create(['must_change_password' => false]);
        $admin->assignRole('Directeur Général Médecin');

        $response = $this->actingAs($admin)->post(route('patients.store'), [
            'nom' => 'Test',
            'prenom' => 'Patient',
            'telephone' => '0612345678',
            'email' => 'test.patient@example.com',
            'date_naissance' => '1990-01-01',
            'sexe' => 'homme',
            'groupe_sanguin' => 'O+',
            'statut_interne_externe' => 'externe',
        ]);

        $response->assertRedirect(route('patients.index'));
        $this->assertDatabaseHas('patients', ['email' => 'test.patient@example.com']);
    }

    public function test_patient_gets_unique_number_on_creation(): void
    {
        $admin = User::factory()->create(['must_change_password' => false]);
        $admin->assignRole('Directeur Général Médecin');

        $this->actingAs($admin)->post(route('patients.store'), [
            'nom' => 'Unique',
            'prenom' => 'Number',
            'telephone' => '0699999999',
            'email' => 'unique@example.com',
            'date_naissance' => '1990-01-01',
            'sexe' => 'femme',
            'statut_interne_externe' => 'interne',
        ]);

        $patient = Patient::where('email', 'unique@example.com')->first();
        $this->assertNotNull($patient->numero_unique);
        $this->assertStringStartsWith('P-', $patient->numero_unique);
    }

    public function test_admin_can_view_patient_details(): void
    {
        $admin = User::factory()->create(['must_change_password' => false]);
        $admin->assignRole('Directeur Général Médecin');

        $patient = Patient::factory()->create();

        $response = $this->actingAs($admin)->get(route('patients.show', $patient));
        $response->assertStatus(200);
        $response->assertSee($patient->nom);
    }

    public function test_unauthorized_user_cannot_view_patients(): void
    {
        $user = User::factory()->create(['must_change_password' => false]);
        $user->assignRole('Réceptionniste');
        // Réceptionniste has 'view patients' — use a role without it
        $user2 = User::factory()->create(['must_change_password' => false]);
        $user2->assignRole('Laborantin');
        // Laborantin has 'view patients' too — let's test with a user with no role
        $noRoleUser = User::factory()->create(['must_change_password' => false]);

        $response = $this->actingAs($noRoleUser)->get(route('patients.index'));
        $response->assertStatus(403);
    }
}
