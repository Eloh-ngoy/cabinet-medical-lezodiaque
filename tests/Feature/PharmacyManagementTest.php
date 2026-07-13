<?php

namespace Tests\Feature;

use App\Models\Medication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PharmacyManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PermissionSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_pharmacien_can_view_pharmacy_index(): void
    {
        $pharmacien = User::factory()->create(['must_change_password' => false]);
        $pharmacien->assignRole('Pharmacien');

        $response = $this->actingAs($pharmacien)->get(route('pharmacy.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_medication(): void
    {
        $admin = User::factory()->create(['must_change_password' => false]);
        $admin->assignRole('Directeur Général Médecin');

        $response = $this->actingAs($admin)->post(route('pharmacy.store'), [
            'name' => 'Test Médicament',
            'generic_name' => 'Test Générique',
            'category' => 'Antalgique',
            'unit' => 'boîte',
            'stock_quantity' => 50,
            'min_stock_threshold' => 10,
            'unit_price' => 5.50,
            'description' => 'Description test',
        ]);

        $response->assertRedirect(route('pharmacy.index'));
        $this->assertDatabaseHas('medications', ['name' => 'Test Médicament']);
    }

    public function test_dispense_decreases_stock(): void
    {
        $pharmacien = User::factory()->create(['must_change_password' => false]);
        $pharmacien->assignRole('Pharmacien');

        $medication = Medication::create([
            'name' => 'Dispense Test',
            'generic_name' => 'Generic',
            'category' => 'Antalgique',
            'unit' => 'boîte',
            'stock_quantity' => 30,
            'min_stock_threshold' => 5,
            'unit_price' => 3.00,
        ]);

        $response = $this->actingAs($pharmacien)->post(route('pharmacy.dispense', $medication), [
            'quantity' => 10,
            'reason' => 'Test dispense',
        ]);

        $response->assertRedirect(route('pharmacy.show', $medication));
        $medication->refresh();
        $this->assertEquals(20, $medication->stock_quantity);
        $this->assertDatabaseHas('medication_movements', [
            'medication_id' => $medication->id,
            'movement_type' => 'delivrance',
            'quantity' => 10,
        ]);
    }

    public function test_restock_increases_stock(): void
    {
        $pharmacien = User::factory()->create(['must_change_password' => false]);
        $pharmacien->assignRole('Pharmacien');

        $medication = Medication::create([
            'name' => 'Restock Test',
            'generic_name' => 'Generic',
            'category' => 'Antalgique',
            'unit' => 'boîte',
            'stock_quantity' => 10,
            'min_stock_threshold' => 5,
            'unit_price' => 2.00,
        ]);

        $response = $this->actingAs($pharmacien)->post(route('pharmacy.restock', $medication), [
            'quantity' => 20,
            'reason' => 'Test restock',
        ]);

        $response->assertRedirect(route('pharmacy.show', $medication));
        $medication->refresh();
        $this->assertEquals(30, $medication->stock_quantity);
    }

    public function test_low_stock_detection(): void
    {
        $medication = Medication::create([
            'name' => 'Low Stock Test',
            'generic_name' => 'Generic',
            'category' => 'Antalgique',
            'unit' => 'boîte',
            'stock_quantity' => 3,
            'min_stock_threshold' => 10,
            'unit_price' => 1.50,
        ]);

        $this->assertTrue($medication->isLowStock());
    }
}
