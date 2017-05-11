@extends('pages::public.master')

@section('bodyClass', 'body-tags body-tags-index body-page body-page-'.$page->id)

@section('content')

    {!! $page->body !!}

    @include('files::public._files', ['model' => $page])

    @if ($models->count())
    @include('tags::public._list', ['items' => $models])
    @endif

    {!! $models->appends(Request::except('page'))->links() !!}

@endsection
