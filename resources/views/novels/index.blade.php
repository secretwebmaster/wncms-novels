@extends('wncms::layouts.backend')

@section('content')
    @include('wncms::backend.parts.message')

    {{-- Toolbar filters --}}
    <div class="wncms-toolbar-filter mt-5">
        <form action="{{ route('novels.index') }}">
            <div class="row gx-1 align-items-center position-relative my-1">
                @include('wncms::backend.common.default_toolbar_filters')

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
                'model_prefix' => 'novels',
                'label_prefix' => __('wncms-novels::word.novel'),
            ])
        </div>
    </div>

    @include('wncms::backend.common.showing_item_of_total', ['models' => $novels])

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
                            <th>@lang('wncms-novels::word.author')</th>
                            <th>@lang('wncms::word.status')</th>
                            <th>@lang('wncms::word.created_at')</th>

                            @if (request()->show_detail)
                                <th>@lang('wncms-novels::word.slug')</th>
                                <th>@lang('wncms-novels::word.label')</th>
                                <th>@lang('wncms-novels::word.description')</th>
                                <th>@lang('wncms-novels::word.remark')</th>
                                <th>@lang('wncms-novels::word.order')</th>
                                <th>@lang('wncms-novels::word.series_status')</th>
                                <th>@lang('wncms-novels::word.word_count')</th>
                                <th>@lang('wncms::word.price')</th>
                                <th>@lang('wncms-novels::word.is_pinned')</th>
                                <th>@lang('wncms-novels::word.is_recommended')</th>
                                <th>@lang('wncms-novels::word.is_dmca')</th>
                                <th>@lang('wncms::word.published_at')</th>
                                <th>@lang('wncms::word.expired_at')</th>
                                <th>@lang('wncms-novels::word.source')</th>
                                <th>@lang('wncms-novels::word.ref_id')</th>
                                <th>@lang('wncms-novels::word.chapter_count')</th>
                                <th>@lang('wncms::word.updated_at')</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody id="table_with_checks" class="fw-semibold text-gray-600">
                        @foreach ($novels as $novel)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" data-model-id="{{ $novel->id }}">
                                    </div>
                                </td>

                                <td>
                                    <a class="btn btn-sm btn-dark fw-bold px-2 py-1" href="{{ route('novels.edit', $novel) }}">@lang('wncms::word.edit')</a>
                                    @include('wncms::backend.parts.modal_delete', ['model' => $novel, 'route' => route('novels.destroy', $novel), 'btn_class' => 'px-2 py-1'])
                                    <a class="btn btn-sm btn-info fw-bold px-2 py-1" href="{{ route('novel_chapters.index', ['novel' => $novel->id]) }}" target="_blank">@lang('wncms-novels::word.chapters')</a>
                                </td>
                                <td>{{ $novel->id }}</td>
                                <td>{{ $novel->title }}</td>
                                <td>{{ $novel->user?->username }} @if ($novel->user?->username)
                                        (#{{ $novel->user?->id }})
                                    @endif
                                </td>

                                {{-- status --}}
                                <td>@include('wncms::common.table_status', ['model' => $novel])</td>

                                <td>{{ $novel->created_at }}</td>

                                @if (request()->show_detail)
                                    <td>{{ $novel->slug }}</td>
                                    <td>{{ $novel->label }}</td>
                                    <td>{{ $novel->description }}</td>
                                    <td>{{ $novel->remark }}</td>
                                    <td>{{ $novel->order }}</td>
                                    <td>{{ $novel->series_status }}</td>
                                    <td>{{ $novel->word_count }}</td>
                                    <td>{{ $novel->price }}</td>

                                    {{-- boolean fields --}}
                                    <td>@include('wncms::common.table_is_active', ['model' => $novel, 'active_column' => 'is_pinned'])</td>
                                    <td>@include('wncms::common.table_is_active', ['model' => $novel, 'active_column' => 'is_recommended'])</td>
                                    <td>@include('wncms::common.table_is_active', ['model' => $novel, 'active_column' => 'is_dmca'])</td>

                                    <td>{{ $novel->published_at }}</td>
                                    <td>{{ $novel->expired_at }}</td>
                                    <td>{{ $novel->source }}</td>
                                    <td>{{ $novel->ref_id }}</td>
                                    <td>{{ $novel->chapter_count }}</td>
                                    <td>{{ $novel->updated_at }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    @include('wncms::backend.common.showing_item_of_total', ['models' => $novels])

    {{-- Pagination --}}
    <div class="mt-5">
        {{ $novels->withQueryString()->links() }}
    </div>
@endsection

@push('foot_js')
    <script>
        $('.model_index_checkbox').on('change', function() {
            $(this).val($(this).is(':checked') ? '1' : '0');
            $(this).closest('form').submit();
        });
    </script>
@endpush
