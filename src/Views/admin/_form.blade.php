@section('js')
    <script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
    <script src="{{ asset('js/admin/form.js') }}"></script>
@stop

@include('core::admin._buttons-form')

{{ Form::hidden('id') }}

<div class="row">
    <div class="col-md-6 form-group @if($errors->has('tag'))has-error @endif">
        {{ Form::label('tag', trans('validation.attributes.tag'), array('class' => 'control-label')) }}
        {{ Form::text('tag', null, array('class' => 'form-control')) }}
        {{ $errors->first('tag', '<p class="help-block">:message</p>') }}
    </div>
    <div class="col-md-6 form-group @if($errors->has('slug'))has-error @endif">
        {{ Form::label('slug', trans('validation.attributes.slug'), array('class' => 'control-label')) }}
        <div class="input-group">
            {{ Form::text('slug', null, array('class' => 'form-control')) }}
            <span class="input-group-btn">
                <button class="btn btn-default btn-slug @if($errors->has('slug'))btn-danger @endif" type="button">@lang('validation.attributes.generate')</button>
            </span>
        </div>
        {{ $errors->first('slug', '<p class="help-block">:message</p>') }}
    </div>
</div>
