<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MaterialManagementController extends Controller
{
    public const PER_PAGE = 6;

    public function index(Request $request): View
    {
        $user = $request->user();
        $search = trim((string) $request->query('q', ''));
        $categoryId = (string) $request->query('category', '');
        $grade = (string) $request->query('grade', '');
        $status = (string) $request->query('status', '');
        $scope = (string) $request->query('scope', '');

        $materials = Material::query()
            ->sma()
            ->with(['category', 'author'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($categoryId !== '', fn ($query) => $query->where('material_category_id', $categoryId))
            ->when(array_key_exists($grade, Material::GRADES), fn ($query) => $query->where('grade_level', $grade))
            ->when(array_key_exists($status, Material::STATUSES), fn ($query) => $query->where('status', $status))
            ->when($scope === 'mine', fn ($query) => $query->where('user_id', $user->id))
            ->latest()
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $categories = MaterialCategory::query()->sma()->orderBy('name')->get();

        return view('materials.manage.index', [
            'materials' => $materials,
            'categories' => $categories,
            'search' => $search,
            'selectedCategory' => $categoryId,
            'selectedGrade' => $grade,
            'selectedStatus' => $status,
            'selectedScope' => $scope,
            'routePrefix' => $this->routePrefix($request),
            'user' => $user,
        ]);
    }

    public function create(Request $request): View
    {
        $categories = MaterialCategory::query()
            ->sma()
            ->active()
            ->orderBy('name')
            ->get();

        return view('materials.manage.create', [
            'material' => new Material([
                'education_level' => Material::LEVEL_SMA,
                'grade_level' => Material::GRADE_10,
                'status' => Material::STATUS_PUBLISHED,
                'estimated_minutes' => 20,
                'material_category_id' => $request->query('category'),
            ]),
            'categories' => $categories,
            'routePrefix' => $this->routePrefix($request),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateMaterial($request);

        $status = $validated['status'];
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('materials', 'public');
        }

        Material::create([
            'material_category_id' => $validated['material_category_id'],
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'slug' => Material::generateUniqueSlug($validated['title']),
            'education_level' => Material::LEVEL_SMA,
            'grade_level' => $validated['grade_level'],
            'description' => $validated['description'],
            'image_path' => $imagePath,
            'image_caption' => $validated['image_caption'] ?? null,
            'content' => $validated['content'],
            'status' => $status,
            'estimated_minutes' => (int) $validated['estimated_minutes'],
            'published_at' => $status === Material::STATUS_PUBLISHED ? now() : null,
        ]);

        return redirect()
            ->route($this->routePrefix($request) . '.materials.index')
            ->with('status', 'Materi pembelajaran SMA berhasil disimpan.');
    }

    public function show(Request $request, Material $material): View
    {
        $material->load(['category', 'author']);

        $relatedMaterials = Material::query()
            ->sma()
            ->where('material_category_id', $material->material_category_id)
            ->whereKeyNot($material->id)
            ->latest()
            ->limit(4)
            ->get();

        return view('materials.manage.show', [
            'material' => $material,
            'relatedMaterials' => $relatedMaterials,
            'routePrefix' => $this->routePrefix($request),
            'canManage' => $this->canModifyMaterial($request->user(), $material),
        ]);
    }

    public function edit(Request $request, Material $material): View
    {
        $this->authorizeModification($request->user(), $material);

        $categories = MaterialCategory::query()
            ->sma()
            ->where(function ($query) use ($material) {
                $query->where('is_active', true)
                    ->orWhere('id', $material->material_category_id);
            })
            ->orderBy('name')
            ->get();

        return view('materials.manage.edit', [
            'material' => $material,
            'categories' => $categories,
            'routePrefix' => $this->routePrefix($request),
        ]);
    }

    public function update(Request $request, Material $material): RedirectResponse
    {
        $this->authorizeModification($request->user(), $material);

        $validated = $this->validateMaterial($request);

        $slug = $material->title !== $validated['title']
            ? Material::generateUniqueSlug($validated['title'], $material->id)
            : $material->slug;

        $status = $validated['status'];
        $publishedAt = $material->published_at;
        if ($status === Material::STATUS_PUBLISHED && $publishedAt === null) {
            $publishedAt = now();
        } elseif ($status === Material::STATUS_DRAFT) {
            $publishedAt = null;
        }

        $imagePath = $material->image_path;
        if ($request->boolean('remove_image')) {
            $this->deleteStoredImageIfLocal($imagePath);
            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            $this->deleteStoredImageIfLocal($imagePath);
            $imagePath = $request->file('image')->store('materials', 'public');
        }

        $material->update([
            'material_category_id' => $validated['material_category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'education_level' => Material::LEVEL_SMA,
            'grade_level' => $validated['grade_level'],
            'description' => $validated['description'],
            'image_path' => $imagePath,
            'image_caption' => $imagePath !== null ? ($validated['image_caption'] ?? null) : null,
            'content' => $validated['content'],
            'status' => $status,
            'estimated_minutes' => (int) $validated['estimated_minutes'],
            'published_at' => $publishedAt,
        ]);

        return redirect()
            ->route($this->routePrefix($request) . '.materials.index')
            ->with('status', 'Materi pembelajaran SMA berhasil diperbarui.');
    }

    public function destroy(Request $request, Material $material): RedirectResponse
    {
        $this->authorizeModification($request->user(), $material);

        $this->deleteStoredImageIfLocal($material->image_path);
        $material->delete();

        return redirect()
            ->route($this->routePrefix($request) . '.materials.index')
            ->with('status', 'Materi pembelajaran SMA berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateMaterial(Request $request): array
    {
        return $request->validate([
            'material_category_id' => ['required', 'integer', 'exists:material_categories,id'],
            'title' => ['required', 'string', 'max:180'],
            'grade_level' => ['required', Rule::in(array_keys(Material::GRADES))],
            'estimated_minutes' => ['required', 'integer', 'min:5', 'max:240'],
            'status' => ['required', Rule::in(array_keys(Material::STATUSES))],
            'description' => ['required', 'string', 'max:1000'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'image_caption' => ['nullable', 'string', 'max:255'],
            'remove_image' => ['nullable', 'boolean'],
            'content' => ['required', 'string', 'min:20'],
        ]);
    }

    private function deleteStoredImageIfLocal(?string $imagePath): void
    {
        if (! is_string($imagePath) || trim($imagePath) === '') {
            return;
        }

        if (Str::startsWith($imagePath, ['http://', 'https://', '/'])) {
            return;
        }

        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    private function canModifyMaterial(User $user, Material $material): bool
    {
        return $user->isSuperAdmin() || ($user->isTeacher() && (int) $material->user_id === (int) $user->id);
    }

    private function authorizeModification(User $user, Material $material): void
    {
        if (! $this->canModifyMaterial($user, $material)) {
            abort(403, 'Anda tidak memiliki izin untuk mengubah materi pembelajaran ini.');
        }
    }

    private function routePrefix(Request $request): string
    {
        return $request->routeIs('admin.*') ? 'admin' : 'teacher';
    }
}
