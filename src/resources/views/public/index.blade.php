@extends('pages::public.master')
@inject('page', 'typicms.tags.page')

@section('bodyClass', 'body-tags body-tags-index body-page body-page-' . $page->id)

@section('main')

    {!! $page->body !!}

    @include('galleries::public._galleries', ['model' => $page])

    @if ($models->count())
    @include('tags::public._list', ['items' => $models])
    @endif

    {!! $models->appends(Input::except('page'))->render() !!}

@stop
