@extends('wncms::layouts.backend')

@section('content')
@include('wncms::backend.parts.message')

<div class="card">
    <div class="card-header px-3 px-md-9">
        <div class="card-title">
            <h3 class="fw-bolder m-0">{{ wncms_model_word(__('wncms-novels::word.novel_chapter'), 'create') }}</h3>
        </div>
    </div>

    <div class="collapse show">
        <form class="form" method="POST" action="{{ route('novel_chapters.store') }}" enctype="multipart/form-data">
            @csrf
            @include('wncms-novels::chapters.form-items')

            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="submit" class="btn btn-primary wncms-submit">
                    @include('wncms::backend.parts.submit', ['label' => __('wncms::word.create')])
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
