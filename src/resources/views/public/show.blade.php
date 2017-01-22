@extends('core::public.master')

@section('title', $model->tag.' – '.__('tags::global.name').' – '.$websiteTitle)
@section('ogTitle', $model->tag)
@section('bodyClass', 'body-tags body-tag-'.$model->id.' body-page body-page-'.$page->id)

@section('content')

    @include('core::public._btn-prev-next', ['module' => 'Tags', 'model' => $model])
    <article>
        <h1>{{ $model->tag }}</h1>
    </article>

@endsection
