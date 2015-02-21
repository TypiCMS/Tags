@section('js')
    <script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
    <script src="{{ asset('js/admin/form.js') }}"></script>
@stop

@include('core::admin._buttons-form')

{!! BootForm::hidden('id') !!}

<div class="row">
    <div class="col-md-6">
        {!! BootForm::text(trans('validation.attributes.tag'), 'tag') !!}
    </div>
    <div class="col-md-6 form-group @if($errors->has('slug'))has-error @endif">
        {!! Form::label(trans('validation.attributes.slug'))->addClass('control-label')->forId('slug') !!}
        <div class="input-group">
            {!! Form::text('slug')->addClass('form-control')->id('slug')->data('slug', 'tag') !!}
            <span class="input-group-btn">
                <button class="btn btn-default btn-slug @if($errors->has('slug'))btn-danger @endif" type="button">@lang('validation.attributes.generate')</button>
            </span>
        </div>
        {!! $errors->first('slug', '<p class="help-block">:message</p>') !!}
    </div>
</div>
