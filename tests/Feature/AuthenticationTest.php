<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_page_displays_login_and_register_links_for_guests(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Nalar');
        $response->assertSee(route('login'), false);
        $response->assertSee(route('register'), false);
    }

    public function test_welcome_page_displays_dashboard_link_for_authenticated_users(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee(url('/dashboard'), false);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('Selamat datang kembali');
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertOk();
        $response->assertSee('Buat akun');
    }

    public function test_new_users_can_register_and_are_redirected_to_dashboard(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Santoso',
            'email' => 'budi@nalar.test',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');

        $user = User::where('email', 'budi@nalar.test')->firstOrFail();
        $this->assertSame(User::ROLE_STUDENT, $user->role);
        $this->assertTrue($user->isStudent());
    }

    public function test_public_registration_cannot_escalate_role_to_teacher_or_super_admin(): void
    {
        foreach ([User::ROLE_TEACHER, User::ROLE_SUPER_ADMIN, 'admin', 'user'] as $index => $attemptedRole) {
            $email = "attempt{$index}@nalar.test";

            $response = $this->post('/register', [
                'name' => "User {$index}",
                'email' => $email,
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'role' => $attemptedRole,
            ]);

            $response->assertRedirect('/dashboard');

            $user = User::where('email', $email)->firstOrFail();
            $this->assertSame(User::ROLE_STUDENT, $user->role);
            $this->assertFalse($user->isTeacher());
            $this->assertFalse($user->isSuperAdmin());

            auth()->logout();
        }
    }

    public function test_new_user_without_explicit_role_defaults_to_student(): void
    {
        $user = new User([
            'name' => 'Siswa Baru',
            'email' => 'siswa.baru@nalar.test',
            'password' => 'Password123!',
        ]);
        $user->save();

        $this->assertSame(User::ROLE_STUDENT, $user->fresh()->role);
    }

    public function test_invalid_role_values_are_rejected_by_application_and_database(): void
    {
        $this->assertSame(['super_admin', 'teacher', 'student'], User::ROLES);

        // Penolakan di tingkat model/aplikasi untuk role tidak resmi
        $user = User::factory()->make();
        $user->role = 'user';

        $this->expectException(\InvalidArgumentException::class);
        $user->save();
    }

    public function test_database_constraint_rejects_non_official_roles(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        \Illuminate\Support\Facades\DB::table('users')->insert([
            'name' => 'Invalid Role User',
            'email' => 'invalid.role@nalar.test',
            'role' => 'user',
            'password' => bcrypt('Password123!'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_registration_validates_required_fields_and_unique_email(): void
    {
        User::factory()->create(['email' => 'existing@nalar.test']);

        $response = $this->post('/register', [
            'name' => '',
            'email' => 'existing@nalar.test',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertGuest();
    }

    public function test_users_can_authenticate_using_the_login_screen_and_redirect_to_dashboard(): void
    {
        $user = User::factory()->student()->create([
            'password' => 'Password123!',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    public function test_existing_users_across_all_official_roles_can_login_after_migration(): void
    {
        foreach ([User::ROLE_SUPER_ADMIN, User::ROLE_TEACHER, User::ROLE_STUDENT] as $role) {
            $user = User::factory()->create([
                'email' => "{$role}@nalar.test",
                'password' => 'Password123!',
                'role' => $role,
            ]);

            $response = $this->post('/login', [
                'email' => $user->email,
                'password' => 'Password123!',
            ]);

            $this->assertAuthenticatedAs($user);
            $response->assertRedirect('/dashboard');
            $this->assertSame($role, $user->fresh()->role);

            auth()->logout();
        }
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_authenticated_users_are_redirected_away_from_login_and_register(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/login')->assertRedirect('/dashboard');
        $this->actingAs($user)->get('/register')->assertRedirect('/dashboard');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
