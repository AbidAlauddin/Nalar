<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\User;
use Database\Seeders\SmaLearningMaterialSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LearningMaterialTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_crud_sma_categories_and_cannot_delete_category_with_materials(): void
    {
        $admin = User::factory()->superAdmin()->create(['name' => 'Super Admin Utama']);
        $teacher = User::factory()->teacher()->create(['name' => 'Guru Fisika']);

        // Create category via web
        $this->actingAs($admin)
            ->post('/admin/categories', [
                'name' => 'Fisika SMA',
                'description' => 'Mekanika, termodinamika, dan gelombang SMA.',
                'is_active' => '1',
            ])
            ->assertRedirect('/admin/categories');

        $category = MaterialCategory::where('slug', 'fisika-sma')->firstOrFail();
        $this->assertSame('SMA', $category->education_level);
        $this->assertSame($admin->id, $category->user_id);
        $this->assertTrue($category->is_active);

        // Categories table displays "Ditambahkan oleh Super Admin Utama"
        $this->actingAs($admin)
            ->get('/admin/categories')
            ->assertOk()
            ->assertSee('Ditambahkan oleh Super Admin Utama');

        // Update category
        $this->actingAs($admin)
            ->put("/admin/categories/{$category->id}", [
                'name' => 'Fisika Terapan SMA',
                'description' => 'Deskripsi diperbarui.',
                'is_active' => '1',
            ])
            ->assertRedirect('/admin/categories');

        $this->assertDatabaseHas('material_categories', [
            'id' => $category->id,
            'name' => 'Fisika Terapan SMA',
            'slug' => 'fisika-terapan-sma',
        ]);

        // Attach a material and verify category detail view and deletion protection
        $material = Material::create([
            'material_category_id' => $category->id,
            'user_id' => $teacher->id,
            'title' => 'Gerak Parabola',
            'slug' => 'gerak-parabola',
            'education_level' => 'SMA',
            'grade_level' => '10',
            'description' => 'Analisis gerak parabola pada bidang dua dimensi.',
            'content' => 'Pembahasan lengkap mengenai gerak parabola sebagai perpaduan GLB dan GLBB.',
            'status' => 'published',
            'estimated_minutes' => 20,
            'published_at' => now(),
        ]);

        // View category detail page as Super Admin
        $this->actingAs($admin)
            ->get("/admin/categories/{$category->id}")
            ->assertOk()
            ->assertSee('Fisika Terapan SMA')
            ->assertSee('Ditambahkan oleh')
            ->assertSee('Super Admin Utama')
            ->assertSee('Gerak Parabola');

        $this->actingAs($admin)
            ->delete("/admin/categories/{$category->id}")
            ->assertRedirect('/admin/categories')
            ->assertSessionHas('error');

        $this->assertDatabaseHas('material_categories', ['id' => $category->id]);

        // After material is removed, category can be deleted
        $material->delete();

        $this->actingAs($admin)
            ->delete("/admin/categories/{$category->id}")
            ->assertRedirect('/admin/categories')
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('material_categories', ['id' => $category->id]);
    }

    public function test_teacher_can_view_categories_read_only_and_category_detail_with_super_admin_fallback(): void
    {
        $superAdmin = User::factory()->superAdmin()->create(['name' => 'Admin Sistem Nalar']);
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->create();

        // Category created without explicit user_id (simulating seeder or manual DB insert)
        $category = MaterialCategory::create([
            'name' => 'Kimia',
            'slug' => 'kimia-sma',
            'education_level' => 'SMA',
            'description' => 'Materi kimia SMA.',
            'is_active' => true,
        ]);

        $this->assertSame($superAdmin->id, $category->user_id);

        // Teacher can view read-only categories list and see "Ditambahkan oleh Admin Sistem Nalar"
        $this->actingAs($teacher)
            ->get('/teacher/categories')
            ->assertOk()
            ->assertSee('Kimia')
            ->assertSee('Ditambahkan oleh Admin Sistem Nalar')
            ->assertDontSee('Tambah Kategori SMA');

        // Teacher can view category detail page
        $this->actingAs($teacher)
            ->get("/teacher/categories/{$category->id}")
            ->assertOk()
            ->assertSee('Kimia')
            ->assertSee('Admin Sistem Nalar')
            ->assertDontSee('Edit Kategori');

        // Teacher cannot access Super Admin category CRUD endpoints
        $this->actingAs($teacher)->get('/admin/categories')->assertForbidden();
        $this->actingAs($teacher)->post('/admin/categories', ['name' => 'Baru'])->assertForbidden();
        $this->actingAs($teacher)->put("/admin/categories/{$category->id}", ['name' => 'Ubah'])->assertForbidden();
        $this->actingAs($teacher)->delete("/admin/categories/{$category->id}")->assertForbidden();

        // Student cannot access teacher or admin category routes
        $this->actingAs($student)->get('/teacher/categories')->assertForbidden();
        $this->actingAs($student)->get('/admin/categories')->assertForbidden();
    }

    public function test_teacher_can_crud_own_materials_and_cannot_modify_other_teachers_materials(): void
    {
        $teacher1 = User::factory()->teacher()->create(['name' => 'Guru Satu']);
        $teacher2 = User::factory()->teacher()->create(['name' => 'Guru Dua']);
        $admin = User::factory()->superAdmin()->create();

        $category = MaterialCategory::create([
            'name' => 'Matematika',
            'slug' => 'matematika-sma',
            'education_level' => 'SMA',
            'is_active' => true,
        ]);

        // Teacher 1 creates material
        $this->actingAs($teacher1)
            ->post('/teacher/materials', [
                'material_category_id' => $category->id,
                'title' => 'Turunan Fungsi Aljabar',
                'grade_level' => '11',
                'estimated_minutes' => 25,
                'status' => 'published',
                'description' => 'Konsep limit menuju turunan fungsi aljabar Kelas XI SMA.',
                'content' => 'Definisi turunan f\'(x) dan aturan rantai pada fungsi aljabar.',
            ])
            ->assertRedirect('/teacher/materials');

        $material = Material::where('slug', 'turunan-fungsi-aljabar')->firstOrFail();
        $this->assertSame($teacher1->id, $material->user_id);
        $this->assertSame('11', $material->grade_level);

        // Teacher 1 can update own material
        $this->actingAs($teacher1)
            ->put("/teacher/materials/{$material->id}", [
                'material_category_id' => $category->id,
                'title' => 'Turunan Fungsi Aljabar dan Aplikasi',
                'grade_level' => '11',
                'estimated_minutes' => 30,
                'status' => 'published',
                'description' => 'Deskripsi diperbarui.',
                'content' => 'Isi pembahasan lengkap mengenai turunan fungsi dan titik stasioner.',
            ])
            ->assertRedirect('/teacher/materials');

        // Teacher 2 cannot edit, update, or delete Teacher 1's material
        $this->actingAs($teacher2)->get("/teacher/materials/{$material->id}/edit")->assertForbidden();
        $this->actingAs($teacher2)->put("/teacher/materials/{$material->id}", [
            'material_category_id' => $category->id,
            'title' => 'Bajakan',
            'grade_level' => '11',
            'estimated_minutes' => 20,
            'status' => 'published',
            'description' => 'Ringkasan.',
            'content' => 'Konten minimal dua puluh karakter untuk pengujian.',
        ])->assertForbidden();
        $this->actingAs($teacher2)->delete("/teacher/materials/{$material->id}")->assertForbidden();

        // Super Admin can edit and delete any teacher's material
        $this->actingAs($admin)->get("/admin/materials/{$material->id}/edit")->assertOk();
        $this->actingAs($admin)->delete("/admin/materials/{$material->id}")->assertRedirect('/admin/materials');
        $this->assertDatabaseMissing('materials', ['id' => $material->id]);
    }

    public function test_student_can_browse_search_filter_and_read_published_sma_materials_only(): void
    {
        $teacher = User::factory()->teacher()->create(['name' => 'Ibu Ratna']);
        $student = User::factory()->student()->create(['name' => 'Andi Siswa']);

        $math = MaterialCategory::create([
            'name' => 'Matematika',
            'slug' => 'matematika-sma',
            'education_level' => 'SMA',
            'is_active' => true,
        ]);

        $biology = MaterialCategory::create([
            'name' => 'Biologi',
            'slug' => 'biologi-sma',
            'education_level' => 'SMA',
            'is_active' => true,
        ]);

        $publishedMath = Material::create([
            'material_category_id' => $math->id,
            'user_id' => $teacher->id,
            'title' => 'Integral Tak Tentu Kelas XII',
            'slug' => 'integral-tak-tentu-kelas-xii',
            'education_level' => 'SMA',
            'grade_level' => '12',
            'description' => 'Memahami anti-turunan fungsi aljabar.',
            'content' => 'Pembahasan lengkap integral tak tentu beserta konstanta integrasi C.',
            'status' => 'published',
            'estimated_minutes' => 20,
            'published_at' => now(),
        ]);

        $publishedBio = Material::create([
            'material_category_id' => $biology->id,
            'user_id' => $teacher->id,
            'title' => 'Keanekaragaman Hayati Indonesia',
            'slug' => 'keanekaragaman-hayati-indonesia',
            'education_level' => 'SMA',
            'grade_level' => '10',
            'description' => 'Tingkat keanekaragaman gen, jenis, dan ekosistem.',
            'content' => 'Pembahasan lengkap keanekaragaman hayati tingkat gen, spesies, dan ekosistem.',
            'status' => 'published',
            'estimated_minutes' => 15,
            'published_at' => now(),
        ]);

        $draftMaterial = Material::create([
            'material_category_id' => $math->id,
            'user_id' => $teacher->id,
            'title' => 'Materi Draf Rahasia',
            'slug' => 'materi-draf-rahasia',
            'education_level' => 'SMA',
            'grade_level' => '10',
            'description' => 'Belum selesai ditulis.',
            'content' => 'Konten draf yang belum dipublikasikan untuk siswa.',
            'status' => 'draft',
            'estimated_minutes' => 15,
            'published_at' => null,
        ]);

        // Catalog shows published materials and hides drafts
        $this->actingAs($student)
            ->get('/materials')
            ->assertOk()
            ->assertSee('Integral Tak Tentu Kelas XII')
            ->assertSee('Keanekaragaman Hayati Indonesia')
            ->assertDontSee('Materi Draf Rahasia');

        // Filter by category
        $this->actingAs($student)
            ->get('/materials?category=biologi-sma')
            ->assertOk()
            ->assertSee('Keanekaragaman Hayati Indonesia')
            ->assertDontSee('Integral Tak Tentu Kelas XII');

        // Filter by grade
        $this->actingAs($student)
            ->get('/materials?grade=12')
            ->assertOk()
            ->assertSee('Integral Tak Tentu Kelas XII')
            ->assertDontSee('Keanekaragaman Hayati Indonesia');

        // Search by keyword
        $this->actingAs($student)
            ->get('/materials?q=Integral')
            ->assertOk()
            ->assertSee('Integral Tak Tentu Kelas XII')
            ->assertDontSee('Keanekaragaman Hayati Indonesia');

        // Read published material detail
        $this->actingAs($student)
            ->get("/materials/{$publishedMath->slug}")
            ->assertOk()
            ->assertSee('Integral Tak Tentu Kelas XII')
            ->assertSee('Pembahasan lengkap integral tak tentu')
            ->assertSee('Kelas XII (12)')
            ->assertSee('Ibu Ratna');

        // Student cannot read draft material (404), but author teacher can preview it
        $this->actingAs($student)
            ->get("/materials/{$draftMaterial->slug}")
            ->assertNotFound();

        $this->actingAs($teacher)
            ->get("/materials/{$draftMaterial->slug}")
            ->assertOk()
            ->assertSee('Mode Pratinjau Pengajar');
    }

    public function test_sma_learning_material_seeder_is_idempotent(): void
    {
        $this->seed(SmaLearningMaterialSeeder::class);
        $this->seed(SmaLearningMaterialSeeder::class);

        $this->assertSame(9, MaterialCategory::sma()->count());
        $this->assertSame(8, Material::sma()->count());
    }

    public function test_table_pagination_only_renders_when_data_exceeds_per_page_limit(): void
    {
        $admin = User::factory()->superAdmin()->create();
        $teacher = User::factory()->teacher()->create();

        // Create 6 categories (<= 6 PER_PAGE limit): pagination must NOT appear
        for ($i = 1; $i <= 6; $i++) {
            MaterialCategory::create([
                'name' => "Kategori Uji {$i}",
                'slug' => "kategori-uji-{$i}",
                'education_level' => 'SMA',
                'is_active' => true,
            ]);
        }

        $this->actingAs($admin)
            ->get('/admin/categories')
            ->assertOk()
            ->assertDontSee('Navigasi halaman tabel');

        // Seed full SMA dataset (9 categories > 6 PER_PAGE, 8 materials > 6 PER_PAGE): pagination MUST appear
        $this->seed(SmaLearningMaterialSeeder::class);

        $this->actingAs($admin)
            ->get('/admin/categories')
            ->assertOk()
            ->assertSee('Navigasi halaman tabel')
            ->assertSee('Berikutnya');

        $this->actingAs($teacher)
            ->get('/teacher/materials')
            ->assertOk()
            ->assertSee('Navigasi halaman tabel')
            ->assertSee('Berikutnya');

        $this->actingAs($teacher)
            ->get('/teacher/materials?page=2')
            ->assertOk()
            ->assertSee('Sebelumnya');
    }

    public function test_teacher_can_upload_display_and_remove_material_illustration_image(): void
    {
        Storage::fake('public');

        $teacher = User::factory()->teacher()->create(['name' => 'Pak Hendra']);
        $student = User::factory()->student()->create();

        $category = MaterialCategory::create([
            'name' => 'Fisika',
            'slug' => 'fisika-sma',
            'education_level' => 'SMA',
            'is_active' => true,
        ]);

        $uploadedImage = UploadedFile::fake()->image('diagram-optik.png', 600, 400);

        $this->actingAs($teacher)
            ->post('/teacher/materials', [
                'material_category_id' => $category->id,
                'title' => 'Pemantulan dan Pembiasan Cahaya',
                'grade_level' => '11',
                'estimated_minutes' => 20,
                'status' => 'published',
                'description' => 'Mempelajari hukum Snellius pada pemantulan dan pembiasan gelombang cahaya.',
                'image' => $uploadedImage,
                'image_caption' => 'Diagram sinar datang, sinar pantul, dan sinar bias pada bidang batas dua medium.',
                'content' => "1. Hukum Pemantulan Cahaya\nSinar datang, garis normal, dan sinar pantul terletak pada satu bidang datar.\nθ_i = θ_r\n\n2. Hukum Snellius Pembiasan\n- Cahaya merambat dari medium renggang ke rapat mendekati garis normal.\n- Cahaya merambat dari medium rapat ke renggang menjauhi garis normal.",
            ])
            ->assertRedirect('/teacher/materials');

        $material = Material::where('slug', 'pemantulan-dan-pembiasan-cahaya')->firstOrFail();
        $this->assertNotNull($material->image_path);
        $this->assertSame('Diagram sinar datang, sinar pantul, dan sinar bias pada bidang batas dua medium.', $material->image_caption);
        Storage::disk('public')->assertExists($material->image_path);

        // Teacher detail page displays the image, caption, and structured sections/TOC
        $this->actingAs($teacher)
            ->get("/teacher/materials/{$material->id}")
            ->assertOk()
            ->assertSee('Ilustrasi Materi')
            ->assertSee('Diagram sinar datang, sinar pantul, dan sinar bias')
            ->assertSee('Daftar Pembahasan')
            ->assertSee('Hukum Pemantulan Cahaya')
            ->assertSee('Hukum Snellius Pembiasan');

        // Student detail page also displays the image, caption, and structured sections
        $this->actingAs($student)
            ->get("/materials/{$material->slug}")
            ->assertOk()
            ->assertSee('Ilustrasi Materi')
            ->assertSee('Diagram sinar datang, sinar pantul, dan sinar bias')
            ->assertSee('Daftar Pembahasan')
            ->assertSee('Hukum Pemantulan Cahaya');

        // Teacher can remove the image via remove_image checkbox on update
        $oldPath = $material->image_path;
        $this->actingAs($teacher)
            ->put("/teacher/materials/{$material->id}", [
                'material_category_id' => $category->id,
                'title' => 'Pemantulan dan Pembiasan Cahaya',
                'grade_level' => '11',
                'estimated_minutes' => 20,
                'status' => 'published',
                'description' => 'Mempelajari hukum Snellius pada pemantulan dan pembiasan gelombang cahaya.',
                'remove_image' => '1',
                'content' => "1. Hukum Pemantulan Cahaya\nSinar datang, garis normal, dan sinar pantul terletak pada satu bidang datar.",
            ])
            ->assertRedirect('/teacher/materials');

        $material->refresh();
        $this->assertNull($material->image_path);
        $this->assertNull($material->image_caption);
        Storage::disk('public')->assertMissing($oldPath);
    }
}
