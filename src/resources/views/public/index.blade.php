@extends('core::public.master')

@section('title', trans('tags::global.name') . ' – ' . $websiteTitle)
@section('ogTitle', trans('tags::global.name'))
@section('bodyClass', 'body-tags-index')

@section('main')

    <h1>@lang('tags::global.name')</h1>

    @if ($models->count())
    @include('tags::public._list', ['items' => $models])
    @endif

    {!! $models->appends(Input::except('page'))->render() !!}

@stop
