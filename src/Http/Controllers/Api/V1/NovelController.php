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
        try {

            // Check feature toggle
            if ($res = $this->checkApiEnabled('enable_api_novel_store')) {
                return $res;
            }

            // Authenticate user
            $user = $this->authenticateByApiToken($request);
            if ($user instanceof JsonResponse) {
                return $user; // return error JSON if auth failed
            }

            $data = $request->validate([
                'title'         => 'required|string|max:255',
                'slug'          => 'nullable|string|max:255|unique:novels,slug',
                'description'   => 'nullable|string',
                'status'        => ['required', Rule::in(['published', 'drafted', 'trashed'])],
                'series_status' => 'nullable|integer',
                'author'        => 'nullable|string|max:100',
            ]);

            $data['slug'] = $data['slug'] ?? str($data['title'])->slug('-');
            $data['published_at'] = now();

            $novel = Novel::create($data);

            // Optional: clear related cache tags
            if (method_exists(wncms()->cache(), 'flush')) {
                wncms()->cache()->flush(['novels']);
            }

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
