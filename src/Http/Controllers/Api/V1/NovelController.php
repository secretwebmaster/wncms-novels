<?php

namespace Secretwebmaster\WncmsNovels\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Secretwebmaster\WncmsNovels\Models\Novel;
use Illuminate\Support\Facades\Log;
use Wncms\Http\Controllers\Api\V1\ApiController;
use Wncms\Http\Resources\BaseResource;

class NovelController extends ApiController
{
    /**
     * Display a paginated list of novels.
     */
    public function index(Request $request)
    {
        $query = Novel::query()
            ->withCount('chapters')
            ->orderByDesc('id');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $novels = $query->paginate($request->get('per_page', 15));

        return BaseResource::collection($novels);
    }

    /**
     * Display a single novel by ID or slug.
     */
    public function show($id)
    {
        $novel = Novel::query()
            ->with('chapters:id,novel_id,title,slug,number')
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        return new BaseResource($novel);
    }

    /**
     * Store a new novel.
     */
    public function store(Request $request): JsonResponse
    {
        // info($request->all());
        try {

            if ($err = $this->checkEnabled('wncms_api_novel_store')) return $err;
            $auth = $this->checkAuthSetting('wncms_api_novel_store', $request);
            if (isset($auth['error'])) return $auth['error'];
            $user = $auth['user'];

            $data = [
                'user_id' => $user ? $user->id : null,
                'status' => $request->input('status') ?? 'published',
                'external_thumbnail' => $request->input('external_thumbnail'),
                'slug' => $request->input('slug') ?? wncms()->getUniqueSlug('novels'),
                'title' => $request->input('title') ?? '(Untitled Novel)',
                'label' => $request->input('label'),
                'description' => $request->input('description'),
                'remark' => $request->input('remark'),
                'order' => $request->input('order'),
                'series_status' => $request->input('series_status') ?? 0,
                'word_count' => $request->input('word_count') ?? 0,
                'password' => $request->input('password'),
                'price' => $request->input('price'),
                'is_pinned' => $request->boolean('is_pinned'),
                'is_recommended' => $request->boolean('is_recommended'),
                'is_dmca' => $request->boolean('is_dmca'),
                'published_at' => now(),
                'expired_at' => $request->input('expired_at') ? \Carbon\Carbon::parse($request->input('expired_at')) : null,
                'source' => $request->input('source'),
                'ref_id' => $request->input('ref_id'),
                'author' => $request->input('author'),
                'chapter_count' => 0,
            ];

            $novel = Novel::where('slug', $data['slug'])->orWhere('title', $data['title'])->first();
            if (!$novel) {
                $novel = Novel::create($data);

                // tag
                if ($request->has('tag')) {
                    $tags = explode(',', $request->input('tag'));
                    $novel->syncTagsWithType($tags, 'novel_tag');
                }

                // category
                if ($request->has('category')) {
                    $categories = explode(',', $request->input('category'));
                    $novel->syncTagsWithType($categories, 'novel_category');
                }
            }else{
                // update specific fields
                info('Novel already exists, updating existing record: ID ' . $novel->id);
            }

            if ($request->has('chapter_title') || $request->has('chapter_content')) {
                if (!empty($request->input('chapter_title'))) {
                    $chapter = $novel->chapters()->where('title', $request->input('chapter_title'))->first();
                    if ($chapter) {
                        if (!empty($request->input('chapter_content')) && $request->input('chapter_content') !== $chapter->content) {
                            $chapter->content = $request->input('chapter_content');
                            $chapter->save();
                        }
                    } else {
                        $novel->chapters()->create([
                            'status'      => 'published',
                            'slug'        => wncms()->getUniqueSlug('novel_chapters'),
                            'title'       => $request->input('chapter_title'),
                            'content'     => $request->input('chapter_content', ''),
                            'published_at' => now(),
                            'user_id'     => $user ? $user->id : null,
                        ]);
                    }
                }
            }

            $novel->updateWordCount();

            wncms()->cache()->flush(['novels']);
            wncms()->cache()->flush(['novel_chapters']);

            return (new BaseResource($novel))
                ->response()
                ->setStatusCode(201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors → 422
            return response()->json([
                'success' => false,
                'message' => '驗證失敗',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            // All other unexpected errors → 500
            Log::error('Novel creation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return response()->json([
                'success' => false,
                'message' => '建立小說時發生錯誤',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing novel.
     */
    public function update(Request $request, $id)
    {
        $novel = Novel::findOrFail($id);

        $data = $request->validate([
            'title'         => 'sometimes|string|max:255',
            'slug'          => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('novels', 'slug')->ignore($novel->id),
            ],
            'description'   => 'nullable|string',
            'status'        => ['sometimes', Rule::in(['published', 'drafted', 'trashed'])],
            'series_status' => 'nullable|integer',
            'author'        => 'nullable|string|max:100',
        ]);

        $novel->update($data);

        return new BaseResource($novel);
    }

    /**
     * Delete a novel and its chapters.
     */
    public function destroy($id)
    {
        $novel = Novel::findOrFail($id);
        $novel->delete();

        return response()->json(['message' => 'Novel deleted successfully.'], Response::HTTP_OK);
    }
}
