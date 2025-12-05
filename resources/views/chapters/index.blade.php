@extends('wncms::layouts.backend')

@section('content')
    @include('wncms::backend.parts.message')

    {{-- Toolbar filters --}}
    <div class="wncms-toolbar-filter mt-5">
        <form action="{{ route('novel_chapters.index') }}">
            <div class="row gx-1 align-items-center position-relative my-1">
                @include('wncms::backend.common.default_toolbar_filters')

                {{-- Novel Selector --}}
                <div class="d-flex align-items-center col-12 col-md-auto mb-3 ms-0 me-1">
                    <input type="text" name="novel" value="{{ request()->novel }}" class="form-control form-control-sm" placeholder="@lang('wncms-novels::word.novel')" value="{{ request()->novel }}" />
                </div>

                {{-- Status --}}
                <div class="col-6 col-md-auto mb-3 ms-0">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">@lang('wncms::word.status')</option>
                        @foreach (['published', 'drafted', 'trashed'] as $status)
                            <option value="{{ $status }}" @if (request()->status === $status) selected @endif>
                                @lang('wncms::word.' . $status)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-md-auto mb-3 ms-0">
                    <input type="submit" class="btn btn-sm btn-primary fw-bold" value="@lang('wncms::word.submit')">
                </div>
            </div>

            {{-- Checkboxes --}}
            <div class="d-flex flex-wrap">
                @foreach (['show_detail'] as $show)
                    <div class="mb-3 ms-0">
                        <div class="form-check form-check-sm form-check-custom me-2">
                            <input class="form-check-input model_index_checkbox" name="{{ $show }}" type="checkbox" @if (request()->{$show}) checked @endif />
                            <label class="form-check-label fw-bold ms-1">@lang('wncms::word.' . $show)</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>
    </div>

    {{-- Toolbar buttons --}}
    <div class="wncms-toolbar-buttons mb-5">
        <div class="card-toolbar flex-row-fluid gap-1">
            @include('wncms::backend.common.default_toolbar_buttons', [
                'model_prefix' => 'novel_chapters',
                'label_prefix' => __('wncms-novels::word.novel_chapters'),
            ])
        </div>
    </div>

    @include('wncms::backend.common.showing_item_of_total', ['models' => $chapters])

    <div class="card card-flush rounded overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-xs table-hover table-bordered align-middle text-nowrap mb-0">
                    <thead class="table-dark">
                        <tr class="text-start fw-bold gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom me-3">
                                    <input class="form-check-input border border-2 border-white" type="checkbox" data-kt-check="true" data-kt-check-target="#table_with_checks .form-check-input" value="1">
                                </div>
                            </th>
                            <th>@lang('wncms::word.action')</th>
                            <th>ID</th>
                            <th>@lang('wncms::word.title')</th>
                            <th>@lang('wncms-novels::word.novel')</th>
                            <th>@lang('wncms::word.status')</th>
                            <th>@lang('wncms::word.number')</th>
                            <th>@lang('wncms::word.created_at')</th>

                            @if (request()->show_detail)
                                <th>@lang('wncms-novels::word.slug')</th>
                                <th>@lang('wncms-novels::word.label')</th>
                                <th>@lang('wncms-novels::word.description')</th>
                                <th>@lang('wncms-novels::word.remark')</th>
                                <th>@lang('wncms-novels::word.order')</th>
                                <th>@lang('wncms::word.price')</th>
                                <th>@lang('wncms::word.published_at')</th>
                                <th>@lang('wncms::word.expired_at')</th>
                                <th>@lang('wncms-novels::word.source')</th>
                                <th>@lang('wncms-novels::word.ref_id')</th>
                                <th>@lang('wncms-novels::word.author')</th>
                                <th>@lang('wncms::word.updated_at')</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody id="table_with_checks" class="fw-semibold text-gray-600">
                        @foreach ($chapters as $chapter)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" data-model-id="{{ $chapter->id }}">
                                    </div>
                                </td>

                                <td>
                                    <a class="btn btn-sm btn-dark fw-bold px-2 py-1" href="{{ route('novel_chapters.edit', $chapter) }}">@lang('wncms::word.edit')</a>
                                    @include('wncms::backend.parts.modal_delete', ['model' => $chapter, 'route' => route('novel_chapters.destroy', $chapter), 'btn_class' => 'px-2 py-1'])
                                </td>

                                <td>{{ $chapter->id }}</td>
                                <td><a href="{{ route('frontend.novels.chapters.show', ['novelSlug' => $chapter->novel->slug, 'chapterSlug' => $chapter->slug]) }}" target="_blank">{{ $chapter->title }}</a></td>
                                <td><a href="{{ route('frontend.novels.show', ['slug' => $chapter->novel->slug]) }}" target="_blank">{{ $chapter->novel->title }}</a></td>

                                {{-- status --}}
                                <td>@include('wncms::common.table_status', ['model' => $chapter])</td>

                                <td>{{ $chapter->number }}</td>
                                <td>{{ $chapter->created_at }}</td>

                                @if (request()->show_detail)
                                    <td>{{ $chapter->slug }}</td>
                                    <td>{{ $chapter->label }}</td>
                                    <td>{{ $chapter->description }}</td>
                                    <td>{{ $chapter->remark }}</td>
                                    <td>{{ $chapter->order }}</td>
                                    <td>{{ $chapter->price }}</td>
                                    <td>{{ $chapter->published_at }}</td>
                                    <td>{{ $chapter->expired_at }}</td>
                                    <td>{{ $chapter->source }}</td>
                                    <td>{{ $chapter->ref_id }}</td>
                                    <td>{{ $chapter->author }}</td>
                                    <td>{{ $chapter->updated_at }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    @include('wncms::backend.common.showing_item_of_total', ['models' => $chapters])
@endsection

@push('foot_js')
    <script>
        $('.model_index_checkbox').on('change', function() {
            $(this).val($(this).is(':checked') ? '1' : '0');
            $(this).closest('form').submit();
        });
    </script>
@endpush
