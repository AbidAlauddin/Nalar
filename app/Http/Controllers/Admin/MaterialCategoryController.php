<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaterialCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaterialCategoryController extends Controller
{
    public const PER_PAGE = 6;

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $categories = MaterialCategory::query()
            ->sma()
            ->with('creator')
            ->withCount(['materials', 'publishedMaterials'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderBy('name')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('admin.categories.index', [
            'categories' => $categories,
            'search' => $search,
            'status' => $status,
            'isManageable' => true,
            'routePrefix' => 'admin',
        ]);
    }

    public function teacherIndex(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $categories = MaterialCategory::query()
            ->sma()
            ->active()
            ->with('creator')
            ->withCount(['materials', 'publishedMaterials'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('admin.categories.index', [
            'categories' => $categories,
            'search' => $search,
            'status' => 'active',
            'isManageable' => false,
            'routePrefix' => 'teacher',
        ]);
    }

    public function show(Request $request, MaterialCategory $category): View
    {
        $category->load('creator')->loadCount(['materials', 'publishedMaterials']);
        $isManageable = $request->routeIs('admin.*');
        $routePrefix = $isManageable ? 'admin' : 'teacher';

        $materials = $category->materials()
            ->with('author')
            ->latest()
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('admin.categories.show', [
            'category' => $category,
            'materials' => $materials,
            'isManageable' => $isManageable,
            'routePrefix' => $routePrefix,
            'user' => $request->user(),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.create', [
            'category' => new MaterialCategory([
                'education_level' => MaterialCategory::LEVEL_SMA,
                'is_active' => true,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        MaterialCategory::create([
            'user_id' => $request->user()?->id,
            'name' => $validated['name'],
            'slug' => MaterialCategory::generateUniqueSlug($validated['name']),
            'education_level' => MaterialCategory::LEVEL_SMA,
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Kategori materi SMA berhasil ditambahkan.');
    }

    public function edit(MaterialCategory $category): View
    {
        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, MaterialCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $slug = $category->name !== $validated['name']
            ? MaterialCategory::generateUniqueSlug($validated['name'], $category->id)
            : $category->slug;

        $category->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'education_level' => MaterialCategory::LEVEL_SMA,
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Kategori materi SMA berhasil diperbarui.');
    }

    public function destroy(MaterialCategory $category): RedirectResponse
    {
        if ($category->materials()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki materi pembelajaran terkait.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Kategori materi SMA berhasil dihapus.');
    }
}
