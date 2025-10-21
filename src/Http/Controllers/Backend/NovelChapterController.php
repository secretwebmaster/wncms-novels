<?php

namespace Secretwebmaster\WncmsNovels\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Wncms\Http\Controllers\Backend\BackendController;

class NovelChapterController extends BackendController
{
    protected string $modelClass = \Secretwebmaster\WncmsNovels\Models\NovelChapter::class;
    protected array $cacheTags = ['novels', 'novel_chapters'];
    protected string $singular = 'novel_chapter';
    protected string $plural = 'novel_chapters';
    protected string $novelModelClass = \Secretwebmaster\WncmsNovels\Models\Novel::class;

    // define model class if the class name is not standard
    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    public function index(Request $request)
    {
        $q = $this->modelClass::query();

        if (in_array($request->status, $this->modelClass::STATUSES)) {
            $q->where('status', $request->status);
        }

        if($request->novel) {
            if (is_numeric($request->novel)) {
                $q->where('novel_id', $request->novel);
            } else {
                $q->whereHas('novel', function ($subq) use ($request) {
                    $subq->where('slug', $request->novel)
                         ->orWhere('title', 'like', '%' . $request->novel . '%');
                });
                // $novel = $this->novelModelClass::query()
                // ->where('slug', $request->novel)
                // ->orWhere('title', $request->novel)
                // ->first();
            }
        }

        if ($request->keyword) {
            $q->where(function ($subq) use ($request) {
                $subq->orWhere('id', $request->keyword)
                    ->orWhere('slug', 'like', "%" . $request->keyword . "%")
                    ->orWhere('title', 'like', "%" . $request->keyword . "%")
                    ->orWhere('label', 'like', "%" . $request->keyword . "%")
                    ->orWhere('remark', 'like', "%" . $request->keyword . "%");
            });
        }

        if (in_array($request->order, $this->modelClass::ORDERS)) {
            $q->orderBy($request->order, in_array($request->sort, ['asc', 'desc']) ? $request->sort : 'desc');
        }

        $chapters = $q->paginate($request->page_size ?? 20);

        $novels = $this->novelModelClass::pluck('title', 'id');

        return $this->view('wncms-novels::chapters.index', [
            'page_title' => __('wncms-novels::word.novel_chapter'),
            'novels'     => $novels,
            'chapters'   => $chapters,
            'orders'     => $this->modelClass::ORDERS,
            'statuses'   => $this->modelClass::STATUSES,
        ]);
    }

    public function create($novelId = null, $id = null)
    {
        if ($novelId) {
            $novel = $this->novelModelClass::find($novelId);
        } else {
            $novel = null;
        }

        $novels = $this->novelModelClass::pluck('title', 'id');

        if ($id) {
            $chapter = $this->modelClass::find($id);
            if (!$chapter) {
                return back()->withMessage(__('wncms::word.model_not_found', [
                    'model_name' => __('wncms::word.' . $this->singular),
                ]));
            }
        } else {
            $chapter = new $this->modelClass;
        }

        return $this->view('wncms-novels::chapters.create', [
            'page_title' => __('wncms-novels::word.novel_chapter'),
            'statuses'   => $this->modelClass::STATUSES,
            'chapter'    => $chapter,
            'novels'     => $this->novelModelClass::pluck('title', 'id'),
        ]);
    }

    public function store(Request $request)
    {
        $duplicated = $this->modelClass::where('slug', $request->slug)->first();
        if ($duplicated) {
            return back()->withInput()->withMessage(__('wncms-novels::word.slug_already_in_use'));
        }

        $chapter = $this->modelClass::create([
            'novel_id'      => $request->novel_id,
            'user_id'       => $request->user_id,
            'status'        => $request->status,
            'slug'          => $request->slug ?? wncms()->getUniqueSlug('novel_chapters'),
            'title'         => $request->title,
            'label'         => $request->label,
            'description'   => $request->description,
            'content'       => $request->content,
            'number'        => $request->number,
            'order'         => $request->order,
            'password'      => $request->password,
            'price'         => $request->price,
            'published_at'  => $request->published_at ?? now(),
            'expired_at'    => $request->expired_at,
            'source'        => $request->source,
            'ref_id'        => $request->ref_id,
            'author'        => $request->author,
        ]);

        $this->flush();

        return redirect()->route('novel_chapters.edit', ['id' => $chapter])
            ->withMessage(__('wncms::word.successfully_created'));
    }


    public function edit($id)
    {
        $chapter = $this->modelClass::find($id);
        if (!$chapter) {
            return back()->withMessage(__('wncms::word.model_not_found', [
                'model_name' => __('wncms::word.' . $this->singular),
            ]));
        }

        return $this->view('wncms-novels::chapters.edit', [
            'page_title' => __('wncms-novels::word.novel_chapter'),
            'chapter'    => $chapter,
            'statuses'   => $this->modelClass::STATUSES,
            'novels'     => \Secretwebmaster\WncmsNovels\Models\Novel::pluck('title', 'id'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $chapter = $this->modelClass::find($id);
        if (!$chapter) {
            return back()->withMessage(__('wncms::word.model_not_found', [
                'model_name' => __('wncms-novels::word.novel_chapter')
            ]));
        }

        $chapter->update([
            'novel_id'      => $request->novel_id ?? $chapter->novel_id,
            'user_id'       => $request->user_id,
            'status'        => $request->status,
            'slug'          => $request->slug ?? wncms()->getUniqueSlug('novel_chapters'),
            'title'         => $request->title,
            'label'         => $request->label,
            'description'   => $request->description,
            'content'       => $request->content,
            'number'        => $request->number,
            'order'         => $request->order,
            'password'      => $request->password,
            'price'         => $request->price,
            'published_at'  => $request->published_at ?? now(),
            'expired_at'    => $request->expired_at,
            'source'        => $request->source,
            'ref_id'        => $request->ref_id,
            'author'        => $request->author,
        ]);

        $this->flush();

        return redirect()->route('novel_chapters.edit', ['id' => $chapter])
            ->withMessage(__('wncms::word.successfully_updated'));
    }
}
