@extends('core::public.master')

@section('title', $model->tag . ' – ' . trans('news::global.name') . ' – ' . $websiteTitle)
@section('ogTitle', $model->tag)
@section('bodyClass', 'body-news body-news-' . $model->id)

@section('main')

    @include('core::public._btn-prev-next', ['module' => 'Tags', 'model' => $model])
    <article>
        <h1>{{ $model->tag }}</h1>
    </article>

@stop
