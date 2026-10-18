<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\TeacherStudentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_student_sees_learner_dashboard_with_honest_empty_states(): void
    {
        $student = User::factory()->student()->create([
            'name' => 'Siti Aminah',
        ]);

        $response = $this->actingAs($student)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Halo, Siti Aminah');
        $response->assertSee('Ruang Belajar');
        $response->assertSee('Siswa');
        $response->assertSee('Belum ada materi pembelajaran');
        $response->assertSee('Modul kuis belum tersedia');
        $response->assertDontSee('SUPER ADMIN');
        $response->assertDontSee('Dashboard Guru');
    }

    public function test_teacher_can_access_teacher_dashboard_with_shared_admin_layout_and_teacher_sidebar(): void
    {
        $teacher = User::factory()->teacher()->create([
            'name' => 'Pak Budi Guru',
            'email' => 'budi.guru@nalar.com',
        ]);

        $response = $this->actingAs($teacher)->get('/dashboard');

        $response->assertOk()
            ->assertSee('Dashboard Guru')
            ->assertSee('Halo, Pak Budi Guru!')
            ->assertSee('budi.guru@nalar.com')
            ->assertSee('GURU')
            ->assertSee('Ruang Pengajar')
            ->assertSee('Kesiapan Modul Ajar')
            ->assertSee('Status Modul Ajar')
            ->assertSee('Materi Pembelajaran')
            ->assertSee('Kategori Materi')
            ->assertSee('Bank Soal')
            ->assertSee('Kuis')
            ->assertSee('AI Pembelajaran')
            ->assertSee('Hasil dan Evaluasi')
            ->assertSee('Pengaturan Akun')
            ->assertSee('Belum ada materi pembelajaran')
            ->assertSee('Modul kuis belum tersedia')
            ->assertDontSee('SUPER ADMIN')
            ->assertDontSee('Penggunaan AI');

        $this->actingAs($teacher)
            ->get('/teacher/dashboard')
            ->assertOk()
            ->assertSee('Dashboard Guru')
            ->assertSee('Ruang Kerja Guru');
    }

    public function test_student_cannot_access_teacher_dashboard_directly(): void
    {
        $student = User::factory()->student()->create();

        $this->get('/teacher/dashboard')->assertRedirect('/login');
        $this->actingAs($student)->get('/teacher/dashboard')->assertForbidden();
    }

    public function test_super_admin_sees_admin_dashboard_with_real_user_count(): void
    {
        User::factory()->count(2)->student()->create();
        $admin = User::factory()->superAdmin()->create([
            'name' => 'Admin Utama',
            'email' => 'admin@nalar.test',
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('SUPER ADMIN');
        $response->assertSee('Halo, Admin Utama!');
        $response->assertSee('admin@nalar.test');
        $response->assertSee('Kesiapan Modul');
        $response->assertSee('Status Modul LMS');
        $response->assertSee('Total pengguna');
        $response->assertSee('3 pengguna aktif di database');
        $response->assertSee('Ringkasan materi');
        $response->assertSee('Aktivitas kuis');
        $response->assertSee('Pengguna baru terdaftar: Admin Utama');

        $this->actingAs($admin)->get('/admin/dashboard')->assertOk();
    }

    public function test_super_admin_middleware_blocks_students_and_teachers_and_allows_super_admin(): void
    {
        Route::middleware(['web', 'auth', 'super.admin'])->get('/admin/_test-probe', fn () => 'ok');

        $student = User::factory()->student()->create();
        $teacher = User::factory()->teacher()->create();
        $admin = User::factory()->superAdmin()->create();

        $this->get('/admin/_test-probe')->assertRedirect('/login');
        $this->actingAs($student)->get('/admin/_test-probe')->assertForbidden();
        $this->actingAs($teacher)->get('/admin/_test-probe')->assertForbidden();
        $this->actingAs($admin)->get('/admin/_test-probe')->assertOk()->assertSee('ok');
    }

    public function test_teacher_student_seeder_is_idempotent_and_preserves_existing_users(): void
    {
        $existingAdmin = User::factory()->superAdmin()->create([
            'name' => 'Existing Super Admin',
            'email' => 'superadmin@nalar.com',
        ]);

        $this->seed(TeacherStudentSeeder::class);
        $this->seed(TeacherStudentSeeder::class);

        $this->assertDatabaseHas('users', [
            'id' => $existingAdmin->id,
            'email' => 'superadmin@nalar.com',
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $this->assertSame(1, User::where('role', User::ROLE_SUPER_ADMIN)->count());
        $this->assertSame(2, User::where('role', User::ROLE_TEACHER)->count());
        $this->assertSame(10, User::where('role', User::ROLE_STUDENT)->count());

        $teacher1 = User::where('email', 'teacher01@nalar.com')->firstOrFail();
        $this->assertSame('Nalar Teacher 01', $teacher1->name);
        $this->assertTrue($teacher1->isTeacher());
        $this->assertTrue(Hash::check('Password123', $teacher1->password));

        $student10 = User::where('email', 'student10@nalar.com')->firstOrFail();
        $this->assertSame('Nalar Student 10', $student10->name);
        $this->assertTrue($student10->isStudent());
        $this->assertTrue(Hash::check('Password123', $student10->password));
    }
}
