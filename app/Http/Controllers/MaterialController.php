<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaterialController extends Controller
{
    public const PER_PAGE = 6;

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $categorySlug = trim((string) $request->query('category', ''));
        $grade = trim((string) $request->query('grade', ''));

        $categories = MaterialCategory::query()
            ->sma()
            ->active()
            ->withCount('publishedMaterials')
            ->orderBy('name')
            ->get();

        $activeCategory = $categorySlug !== ''
            ? $categories->firstWhere('slug', $categorySlug)
            : null;

        $materials = Material::query()
            ->sma()
            ->published()
            ->whereHas('category', fn ($q) => $q->where('is_active', true))
            ->with(['category', 'author'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($activeCategory !== null, fn ($query) => $query->where('material_category_id', $activeCategory->id))
            ->when(array_key_exists($grade, Material::GRADES), fn ($query) => $query->where('grade_level', $grade))
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('materials.index', [
            'materials' => $materials,
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'search' => $search,
            'selectedCategory' => $categorySlug,
            'selectedGrade' => $grade,
        ]);
    }

    public function show(Request $request, Material $material): View
    {
        $material->load(['category', 'author']);
        $user = $request->user();

        $isAccessibleToStudent = $material->isPublished()
            && $material->education_level === Material::LEVEL_SMA
            && ($material->category?->is_active ?? false);

        $canPreviewDraft = $user !== null
            && ($user->isSuperAdmin() || ($user->isTeacher() && (int) $material->user_id === (int) $user->id));

        if (! $isAccessibleToStudent && ! $canPreviewDraft) {
            abort(404);
        }

        $relatedMaterials = Material::query()
            ->sma()
            ->published()
            ->where('material_category_id', $material->material_category_id)
            ->whereKeyNot($material->id)
            ->orderByDesc('published_at')
            ->limit(4)
            ->get();

        return view('materials.show', [
            'material' => $material,
            'relatedMaterials' => $relatedMaterials,
        ]);
    }
}
