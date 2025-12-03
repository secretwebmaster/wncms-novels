<?php

namespace Secretwebmaster\WncmsNovels\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Secretwebmaster\WncmsNovels\Models\Novel;
use Secretwebmaster\WncmsNovels\Models\NovelChapter;
use Wncms\Http\Resources\BaseResource;
use Str;

class NovelChapterController extends Controller
{
    /**
     * Display a list of chapters (optionally filtered by novel).
     */
    public function index(Request $request)
    {
        $query = NovelChapter::query()->orderBy('number');

        if ($request->filled('novel_id')) {
            $query->where('novel_id', $request->input('novel_id'));
        }

        $chapters = $query->paginate($request->get('per_page', 20));

        return BaseResource::collection($chapters);
    }

    /**
     * Display a single chapter by ID or slug.
     */
    public function show($id)
    {
        $chapter = NovelChapter::query()
            ->with('novel:id,title,slug')
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        return new BaseResource($chapter);
    }

    /**
     * Store a new chapter.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'novel_id' => 'required|integer|exists:novels,id',
                'title'    => 'required|string|max:255',
                'content'  => 'nullable|string',
                'status'   => ['required', Rule::in(['published', 'drafted', 'trashed'])],
                'number'   => 'nullable|integer',
                'slug'     => 'nullable|string|max:255|unique:novel_chapters,slug',
            ]);

            // --- Always ensure slug is unique and not numeric-only
            $baseSlug = $data['slug'] ?? Str::slug($data['title'], '-');
            $uniqueSlug = $baseSlug;
            $counter = 1;

            while (NovelChapter::where('slug', $uniqueSlug)->exists()) {
                $uniqueSlug = "{$baseSlug}-{$counter}";
                $counter++;
            }

            $data['slug'] = $uniqueSlug;
            $data['published_at'] = now();

            $chapter = NovelChapter::create($data);

            if (method_exists(wncms()->cache(), 'flush')) {
                wncms()->cache()->flush(['novel_chapters']);
            }

            return (new BaseResource($chapter))
                ->response()
                ->setStatusCode(201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => '驗證失敗',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Chapter creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => '建立章節時發生錯誤',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Update a chapter.
     */
    public function update(Request $request, $id)
    {
        $chapter = NovelChapter::findOrFail($id);

        $data = $request->validate([
            'title'   => 'sometimes|string|max:255',
            'slug'    => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('novel_chapters', 'slug')->ignore($chapter->id),
            ],
            'content' => 'nullable|string',
            'status'  => ['sometimes', Rule::in(['published', 'drafted', 'trashed'])],
            'author'  => 'nullable|string|max:100',
        ]);

        $chapter->update($data);

        return new BaseResource($chapter);
    }

    /**
     * Delete a chapter.
     */
    public function destroy($id)
    {
        $chapter = NovelChapter::findOrFail($id);
        $chapter->delete();

        return response()->json(['message' => 'Chapter deleted successfully.'], Response::HTTP_OK);
    }
}
