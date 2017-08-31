@extends('core::admin.master')

@section('title', __('New tag'))

@section('content')

    @include('core::admin._button-back', ['module' => 'tags'])
    <h1>
        @lang('New tag')
    </h1>

    {!! BootForm::open()->action(route('admin::index-tags'))->multipart()->role('form') !!}
        @include('tags::admin._form')
    {!! BootForm::close() !!}

@endsection
