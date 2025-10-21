<div class="card-body border-top p-3 p-md-9">

    {{-- user_id --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.user')</label>
        <div class="col-lg-9 fv-row">
            <select id="user" name="user_id" class="form-select form-select-sm">
                <option value="">@lang('wncms::word.please_select') @lang('wncms::word.user')</option>
                @foreach(($users ?? []) as $user)
                    <option value="{{ $user->id }}" {{ (int)$user->id === (int)old('user_id', $novel->user_id ?? null) ? 'selected' : '' }}>
                        {{ $user->username }} #{{ $user->id }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- status --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label required fw-bold fs-6">@lang('wncms::word.status')</label>
        <div class="col-lg-9 fv-row">
            <select name="status" class="form-select form-select-sm" required>
                @foreach(['published', 'drafted', 'trashed'] as $status)
                    <option value="{{ $status }}" {{ $status === old('status', $novel->status ?? 'published') ? 'selected' : '' }}>
                        @lang('wncms::word.' . $status)
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- slug --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.slug')</label>
        <div class="col-lg-9 fv-row">
            <input type="text" name="slug" class="form-control form-control-sm" value="{{ old('slug', $novel->slug ?? '') }}">
        </div>
    </div>

    {{-- novel_category --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms-novels::word.novel_category')</label>
        <div class="col-lg-9 fv-row">
            <input id="novel_categories" class="form-control form-control-sm p-0" name="novel_categories" value="{{ $novel->tagsWithType('novel_category')->implode('name', ',') }}" />
        </div>
        <script type="text/javascript">
            (function () {
                window.addEventListener('DOMContentLoaded', function () {
                    var input = document.querySelector("#novel_categories");
                    if (!input) return;
                    var novel_categories = @json($novel_categories);
                    new Tagify(input, {
                        whitelist: novel_categories,
                        maxTags: 10,
                        tagTextProp: 'value',
                        dropdown: {
                            maxItems: 20,
                            classname: "tagify__inline__suggestions",
                            enabled: 0,
                            closeOnSelect: false,
                            originalInputValueFormat: function(valuesArr){ return valuesArr.map(function(item){ return item.value }).join(',') },
                            mapValueTo: 'name',
                            searchKeys: ['name','value'],
                        }
                    });
                });
            })();
        </script>
    </div>

    {{-- novel_tag --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms-novels::word.novel_tag')</label>
        <div class="col-lg-9 fv-row">
            <input id="novel_tags" class="form-control form-control-sm p-0" name="novel_tags" value="{{ $novel->tagsWithType('novel_tag')->implode('name', ',') }}" />
        </div>
        <script type="text/javascript">
            (function () {
                window.addEventListener('DOMContentLoaded', function () {
                    var input = document.querySelector("#novel_tags");
                    if (!input) return;
                    var novel_tags = @json($novel_tags);
                    new Tagify(input, {
                        whitelist: novel_tags,
                        maxTags: 10,
                        tagTextProp: 'value',
                        dropdown: {
                            maxItems: 20,
                            classname: "tagify__inline__suggestions",
                            enabled: 0,
                            closeOnSelect: false,
                            originalInputValueFormat: function(valuesArr){ return valuesArr.map(function(item){ return item.value }).join(',') },
                            mapValueTo: 'name',
                            searchKeys: ['name','value'],
                        }
                    });
                });
            })();
        </script>
    </div>

    {{-- title --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label required fw-bold fs-6">@lang('wncms::word.title')</label>
        <div class="col-lg-9 fv-row">
            <input type="text" name="title" class="form-control form-control-sm" required value="{{ old('title', $novel->title ?? '') }}">
        </div>
    </div>

    {{-- label --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.label')</label>
        <div class="col-lg-9 fv-row">
            <input type="text" name="label" class="form-control form-control-sm" value="{{ old('label', $novel->label ?? '') }}">
        </div>
    </div>

    {{-- description --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.description')</label>
        <div class="col-lg-9 fv-row">
            <textarea name="description" class="form-control" rows="5">{{ old('description', $novel->description ?? '') }}</textarea>
        </div>
    </div>

    {{-- remark --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.remark')</label>
        <div class="col-lg-9 fv-row">
            <input type="text" name="remark" class="form-control form-control-sm" value="{{ old('remark', $novel->remark ?? '') }}">
        </div>
    </div>

    {{-- external_thumbnail --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms-novels::word.external_thumbnail')</label>
        <div class="col-lg-9 fv-row">
            <input type="text" name="external_thumbnail" class="form-control form-control-sm" value="{{ old('external_thumbnail', $novel->external_thumbnail ?? '') }}">
        </div>
    </div>

    {{-- series_status --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms-novels::word.series_status')</label>
        <div class="col-lg-9 fv-row">
            <select name="series_status" class="form-select form-select-sm">
                @foreach([0 => 'ongoing', 1 => 'completed', 2 => 'paused', 3 => 'dropped', 4 => 'upcoming'] as $value => $label)
                    <option value="{{ $value }}" {{ (int)$value === (int)old('series_status', $novel->series_status ?? 0) ? 'selected' : '' }}>
                        @lang('wncms-novels::word.' . $label)
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- author --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.author')</label>
        <div class="col-lg-9 fv-row">
            <input type="text" name="author" class="form-control form-control-sm" value="{{ old('author', $novel->author ?? '') }}">
        </div>
    </div>

    {{-- word_count --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.word_count')</label>
        <div class="col-lg-9 fv-row">
            <input type="number" name="word_count" class="form-control form-control-sm" value="{{ old('word_count', $novel->word_count ?? '') }}">
        </div>
    </div>

    {{-- price --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.price')</label>
        <div class="col-lg-3 fv-row">
            <input type="number" step="0.001" name="price" class="form-control form-control-sm" value="{{ old('price', $novel->price ?? '') }}">
        </div>
    </div>

    {{-- password --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.password')</label>
        <div class="col-lg-9 fv-row">
            <input type="text" name="password" class="form-control form-control-sm" value="{{ old('password', $novel->password ?? '') }}">
        </div>
    </div>

    {{-- order --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.order')</label>
        <div class="col-lg-9 fv-row">
            <input type="number" name="order" class="form-control form-control-sm" value="{{ old('order', $novel->order ?? null) }}">
        </div>
    </div>

    {{-- is_recommended --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.is_recommended')</label>
        <div class="col-lg-9 d-flex align-items-center">
            <div class="form-check form-check-solid form-check-custom form-switch">
                <input type="hidden" name="is_recommended" value="0">
                <input class="form-check-input w-35px h-20px" type="checkbox" name="is_recommended" value="1"
                       {{ old('is_recommended', $novel->is_recommended ?? false) ? 'checked' : '' }}/>
            </div>
        </div>
    </div>

    {{-- is_pinned --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.is_pinned')</label>
        <div class="col-lg-9 d-flex align-items-center">
            <div class="form-check form-check-solid form-check-custom form-switch">
                <input type="hidden" name="is_pinned" value="0">
                <input class="form-check-input w-35px h-20px" type="checkbox" name="is_pinned" value="1"
                       {{ old('is_pinned', $novel->is_pinned ?? false) ? 'checked' : '' }}/>
            </div>
        </div>
    </div>

    {{-- is_dmca --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.is_dmca')</label>
        <div class="col-lg-9 d-flex align-items-center">
            <div class="form-check form-check-solid form-check-custom form-switch">
                <input type="hidden" name="is_dmca" value="0">
                <input class="form-check-input w-35px h-20px" type="checkbox" name="is_dmca" value="1"
                       {{ old('is_dmca', $novel->is_dmca ?? false) ? 'checked' : '' }}/>
            </div>
        </div>
    </div>

    {{-- published_at --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.published_at')</label>
        <div class="col-lg-9 fv-row">
            <input type="datetime-local" name="published_at" class="form-control form-control-sm"
                   value="{{ old('published_at', isset($novel->published_at) ? $novel->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
        </div>
    </div>

    {{-- expired_at --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.expired_at')</label>
        <div class="col-lg-9 fv-row">
            <input type="datetime-local" name="expired_at" class="form-control form-control-sm"
                   value="{{ old('expired_at', isset($novel->expired_at) ? $novel->expired_at->format('Y-m-d\TH:i') : '') }}">
        </div>
    </div>

    {{-- source --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.source')</label>
        <div class="col-lg-9 fv-row">
            <input type="text" name="source" class="form-control form-control-sm" value="{{ old('source', $novel->source ?? '') }}">
        </div>
    </div>

    {{-- ref_id --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.ref_id')</label>
        <div class="col-lg-9 fv-row">
            <input type="text" name="ref_id" class="form-control form-control-sm" value="{{ old('ref_id', $novel->ref_id ?? '') }}">
        </div>
    </div>

    {{-- chapter_count (readonly) --}}
    @if(isset($novel))
        <div class="row mb-3">
            <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms-novels::word.chapter_count')</label>
            <div class="col-lg-9 fv-row pt-2">
                <input type="text" class="form-control form-control-sm" value="{{ old('chapter_count', $novel->chapter_count ?? '') }}" disabled>
                <a href="{{ route('novel_chapters.create', ['novel' => $novel]) }}" class="btn btn-sm btn-primary mt-1">@lang('wncms-novels::word.add_chapter')</a>
            </div>
        </div>
    @endif

</div>
