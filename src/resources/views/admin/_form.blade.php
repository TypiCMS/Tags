@component('core::admin._buttons-form', ['model' => $model])
@endcomponent

{!! BootForm::hidden('id') !!}

<div class="row">
    <div class="col-md-6">
        {!! BootForm::text(__('Tag'), 'tag') !!}
    </div>
    <div class="col-md-6 form-group @if($errors->has('slug'))has-error @endif">
        {!! Form::label(__('Slug'))->addClass('control-label')->forId('slug') !!}
        <div class="input-group">
            {!! Form::text('slug')->addClass('form-control')->id('slug')->data('slug', 'tag') !!}
            <span class="input-group-btn">
                <button class="btn btn-default btn-slug @if($errors->has('slug'))btn-danger @endif" type="button">{{ __('Generate') }}</button>
            </span>
        </div>
        {!! $errors->first('slug', '<p class="help-block">:message</p>') !!}
    </div>
</div>
