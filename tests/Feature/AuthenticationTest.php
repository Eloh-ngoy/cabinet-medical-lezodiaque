<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PermissionSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('MediNexus');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
            'must_change_password' => false,
        ]);
        $user->assignRole('Médecin');

        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'username' => 'wrong',
            'password' => 'wrong',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create([
            'username' => 'logoutuser',
            'password' => bcrypt('password123'),
            'must_change_password' => false,
        ]);
        $user->assignRole('Médecin');

        $this->actingAs($user);
        $response = $this->post('/logout');
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_first_login_redirects_to_password_change(): void
    {
        $user = User::factory()->create([
            'username' => 'newuser',
            'password' => bcrypt('password123'),
            'must_change_password' => true,
        ]);
        $user->assignRole('Médecin');

        $this->actingAs($user);
        $response = $this->get('/dashboard');
        $response->assertRedirect(route('password.change'));
    }

    public function test_dashboard_is_accessible_for_authenticated_user(): void
    {
        $user = User::factory()->create([
            'username' => 'dashuser',
            'password' => bcrypt('password123'),
            'must_change_password' => false,
        ]);
        $user->assignRole('Directeur Général Médecin');

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Tableau de bord');
    }
}
