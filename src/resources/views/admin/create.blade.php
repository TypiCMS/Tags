@extends('core::admin.master')

@section('title', trans('tags::global.New'))

@section('main')

    @include('core::admin._button-back', ['module' => 'tags'])
    <h1>
        @lang('tags::global.New')
    </h1>

    {!! BootForm::open()->action(route('admin::index-tags'))->multipart()->role('form') !!}
        @include('tags::admin._form')
    {!! BootForm::close() !!}

@endsection
