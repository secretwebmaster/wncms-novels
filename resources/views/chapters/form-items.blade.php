<div class="card-body border-top p-3 p-md-9">

    {{-- novel_id --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label required fw-bold fs-6">@lang('wncms-novels::word.novel')</label>
        <div class="col-lg-9 fv-row">
            @php
                $selectedNovelId = old('novel_id', $chapter->novel_id ?? request()->query('novel'));
                $isFixed = isset($chapter) && $chapter->novel_id; // Fixed only when editing existing chapter
            @endphp

            <select name="novel_id" class="form-select form-select-sm" {{ $isFixed ? 'disabled' : '' }} required>
                <option value="">@lang('wncms::word.please_select')</option>
                @foreach($novels ?? [] as $id => $title)
                    <option value="{{ $id }}" {{ (int)$id === (int)$selectedNovelId ? 'selected' : '' }}>
                        {{ $title }}
                    </option>
                @endforeach
            </select>

            @if($isFixed)
                <a href="{{ route('novels.edit', ['id' => $chapter->novel_id]) }}" class="link-primary mt-1" target="_blank">
                    @lang('wncms-novels::word.novel_detail')
                </a>
            @endif
        </div>
    </div>

    {{-- user_id --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.user')</label>
        <div class="col-lg-9 fv-row">
            <select name="user_id" class="form-select form-select-sm">
                <option value="">@lang('wncms::word.please_select') @lang('wncms::word.user')</option>
                @foreach($users ?? [] as $user)
                    <option value="{{ $user->id }}" {{ (int)$user->id === (int)old('user_id', $chapter->user_id ?? null) ? 'selected' : '' }}>
                        {{ $user->username }} #{{ $user->id }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- status --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.status')</label>
        <div class="col-lg-9 fv-row">
            <select name="status" class="form-select form-select-sm">
                @foreach(['published', 'drafted', 'trashed'] as $status)
                    <option value="{{ $status }}" {{ $status === old('status', $chapter->status ?? 'published') ? 'selected' : '' }}>
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
            <input type="text" name="slug" class="form-control form-control-sm"
                   value="{{ old('slug', $chapter->slug ?? '') }}">
        </div>
    </div>

    {{-- title --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.title')</label>
        <div class="col-lg-9 fv-row">
            <input type="text" name="title" class="form-control form-control-sm"
                   value="{{ old('title', $chapter->title ?? '') }}">
        </div>
    </div>

    {{-- label --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.label')</label>
        <div class="col-lg-9 fv-row">
            <input type="text" name="label" class="form-control form-control-sm"
                   value="{{ old('label', $chapter->label ?? '') }}">
        </div>
    </div>

    {{-- description --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.description')</label>
        <div class="col-lg-9 fv-row">
            <textarea name="description" class="form-control" rows="4">{{ old('description', $chapter->description ?? '') }}</textarea>
        </div>
    </div>

    {{-- content --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.content')</label>
        <div class="col-lg-9 fv-row">
            {{-- <textarea name="content" class="form-control" rows="12">{{ old('content', $chapter->content ?? '') }}</textarea> --}}
            <textarea id="kt_docs_tinymce_basic" name="content" class="tox-target">{{ old('content', $chapter->content) }}</textarea>
        </div>
    </div>

    {{-- number --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms-novels::word.chapter_number')</label>
        <div class="col-lg-3 fv-row">
            <input type="number" name="number" class="form-control form-control-sm"
                   value="{{ old('number', $chapter->number ?? '') }}">
        </div>
    </div>

    {{-- order --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.order')</label>
        <div class="col-lg-9 fv-row">
            <input type="number" name="order" class="form-control form-control-sm"
                   value="{{ old('order', $chapter->order ?? '') }}">
        </div>
    </div>

    {{-- price --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.price')</label>
        <div class="col-lg-3 fv-row">
            <input type="number" step="0.001" name="price" class="form-control form-control-sm"
                   value="{{ old('price', $chapter->price ?? '') }}">
        </div>
    </div>

    {{-- password --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.password')</label>
        <div class="col-lg-9 fv-row">
            <input type="text" name="password" class="form-control form-control-sm"
                   value="{{ old('password', $chapter->password ?? '') }}">
        </div>
    </div>

    {{-- published_at --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.published_at')</label>
        <div class="col-lg-9 fv-row">
            <input type="datetime-local" name="published_at" class="form-control form-control-sm"
                   value="{{ old('published_at', isset($chapter->published_at) ? $chapter->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
        </div>
    </div>

    {{-- expired_at --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.expired_at')</label>
        <div class="col-lg-9 fv-row">
            <input type="datetime-local" name="expired_at" class="form-control form-control-sm"
                   value="{{ old('expired_at', isset($chapter->expired_at) ? $chapter->expired_at->format('Y-m-d\TH:i') : '') }}">
        </div>
    </div>

    {{-- source --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.source')</label>
        <div class="col-lg-9 fv-row">
            <input type="text" name="source" class="form-control form-control-sm"
                   value="{{ old('source', $chapter->source ?? '') }}">
        </div>
    </div>

    {{-- ref_id --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.ref_id')</label>
        <div class="col-lg-9 fv-row">
            <input type="text" name="ref_id" class="form-control form-control-sm"
                   value="{{ old('ref_id', $chapter->ref_id ?? '') }}">
        </div>
    </div>

    {{-- author --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('wncms::word.author')</label>
        <div class="col-lg-9 fv-row">
            <input type="text" name="author" class="form-control form-control-sm"
                   value="{{ old('author', $chapter->author ?? '') }}">
        </div>
    </div>

</div>

@include('wncms::common.js.tinymce')