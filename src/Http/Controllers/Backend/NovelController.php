<?php

namespace Secretwebmaster\WncmsNovels\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Wncms\Http\Controllers\Backend\BackendController;

class NovelController extends BackendController
{
    protected string $modelClass = \Secretwebmaster\WncmsNovels\Models\Novel::class;
    protected array $cacheTags = ['novels'];
    protected string $singular = 'novel';
    protected string $plural = 'novels';

    public function index(Request $request)
    {
        $q = $this->modelClass::query();

        if (in_array($request->status, $this->modelClass::STATUSES)) {
            $q->where('status', $request->status);
        }

        if ($request->keyword) {
            $q->where(function ($subq) use ($request) {
                $subq->orWhere('id', $request->keyword)
                    ->orWhere('slug', 'like', "%" . $request->keyword . "%")
                    ->orWhere('title', 'like', "%" . $request->keyword . "%")
                    ->orWhere('label', 'like', "%" . $request->keyword . "%")
                    ->orWhere('author', 'like', "%" . $request->keyword . "%")
                    ->orWhere('remark', 'like', "%" . $request->keyword . "%");
            });
        }

        if (in_array($request->order, $this->modelClass::ORDERS)) {
            $q->orderBy($request->order, in_array($request->sort, ['asc', 'desc']) ? $request->sort : 'desc');
        }

        $q->with('user');

        $q->orderBy('id', 'desc');

        $novels = $q->paginate($request->page_size ?? 20);

        return $this->view('wncms-novels::novels.index', [
            'page_title' => __('wncms-novels::word.novel'),
            'novels'     => $novels,
            'orders'     => $this->modelClass::ORDERS,
            'statuses'   => $this->modelClass::STATUSES,
        ]);
    }

    public function create($id = null)
    {
        if ($id) {
            $novel = $this->modelClass::find($id);
            if (!$novel) {
                return back()->withMessage(__('wncms::word.model_not_found', [
                    'model_name' => __('wncms::word.' . $this->singular),
                ]));
            }
        } else {
            $novel = new $this->modelClass;
        }

        $novelTags = wncms()->tag()->getTagifyDropdownItems('novel_tag', 'name', 'name', false);
        $novelCategories = wncms()->tag()->getTagifyDropdownItems('novel_category', 'name', 'name', false);

        return $this->view('wncms-novels::novels.create', [
            'page_title' => __('wncms-novels::word.novel'),
            'statuses'   => $this->modelClass::STATUSES,
            'novel'      => $novel,
            'novel_tags' => $novelTags,
            'novel_categories' => $novelCategories,
        ]);
    }

    public function store(Request $request)
    {
        $duplicated = $this->modelClass::where('slug', $request->slug)->first();
        if ($duplicated) {
            return back()->withInput()->withMessage(__('wncms-novels::word.slug_already_in_use'));
        }

        $novel = $this->modelClass::create([
            'user_id'       => $request->user_id,
            'status'        => $request->status,
            'slug'          => $request->slug ?? wncms()->getUniqueSlug('novels'),
            'title'         => $request->title,
            'description'   => $request->description,
            'label'         => $request->label,
            'remark'        => $request->remark,
            'external_thumbnail' => $request->external_thumbnail,
            'series_status' => $request->series_status,
            'order'         => $request->order,
            'word_count'    => $request->word_count,
            'author'        => $request->author,
            'price'         => $request->price,
            'password'      => $request->password,
            'is_pinned'     => $request->is_pinned == 1,
            'is_recommended' => $request->is_recommended == 1,
            'is_dmca'       => $request->is_dmca == 1,
            'published_at'  => $request->published_at ?? now(),
            'expired_at'    => $request->expired_at,
            'source'        => $request->source,
            'ref_id'        => $request->ref_id,
        ]);

        // Tags
        $novel->syncTagsFromTagify($request->novel_tags, 'novel_tag');
        $novel->syncTagsFromTagify($request->novel_categories, 'novel_category');

        $this->flush();

        return redirect()->route('novels.edit', ['id' => $novel])
            ->withMessage(__('wncms::word.successfully_created'));
    }

    public function edit($id)
    {
        $novel = $this->modelClass::find($id);
        if (!$novel) {
            return back()->withMessage(__('wncms::word.model_not_found', [
                'model_name' => __('wncms::word.' . $this->singular),
            ]));
        }

        $novelTags = wncms()->tag()->getTagifyDropdownItems('novel_tag', 'name', 'name', false);
        $novelCategories = wncms()->tag()->getTagifyDropdownItems('novel_category', 'name', 'name', false);

        $users = wncms()->getModel('user')::all();

        return $this->view('wncms-novels::novels.edit', [
            'page_title' => __('wncms-novels::word.novel'),
            'novel'      => $novel,
            'statuses'   => $this->modelClass::STATUSES,
            'novel_tags' => $novelTags,
            'novel_categories' => $novelCategories,
            'users'      => $users,
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $novel = $this->modelClass::find($id);
        if (!$novel) {
            return back()->withMessage(__('wncms::word.model_not_found', [
                'model_name' => __('wncms::word.' . $this->singular),
            ]));
        }

        $novel->update([
            'user_id'       => $request->user_id,
            'status'        => $request->status,
            'slug'          => $request->slug ?? wncms()->getUniqueSlug('novels'),
            'title'         => $request->title,
            'description'   => $request->description,
            'label'         => $request->label,
            'remark'        => $request->remark,
            'external_thumbnail' => $request->external_thumbnail,
            'series_status' => $request->series_status,
            'order'         => $request->order,
            'word_count'    => $request->word_count,
            'author'        => $request->author,
            'price'         => $request->price,
            'password'      => $request->password,
            'is_pinned'     => $request->is_pinned == 1,
            'is_recommended' => $request->is_recommended == 1,
            'is_dmca'       => $request->is_dmca == 1,
            'published_at'  => $request->published_at ?? now(),
            'expired_at'    => $request->expired_at,
            'source'        => $request->source,
            'ref_id'        => $request->ref_id,
        ]);

        // Tags
        $novel->syncTagsFromTagify($request->novel_tags, 'novel_tag');
        $novel->syncTagsFromTagify($request->novel_categories, 'novel_category');

        $this->flush();

        return redirect()->route('novels.edit', ['id' => $novel])
            ->withMessage(__('wncms::word.successfully_updated'));
    }
}
