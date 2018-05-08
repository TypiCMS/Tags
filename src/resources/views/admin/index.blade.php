@extends('core::admin.master')

@section('title', __('Tags'))

@section('content')

<div ng-cloak ng-controller="ListController">

    @include('core::admin._button-create', ['module' => 'tags'])

    <h1>@lang('Tags')</h1>

    <div class="btn-toolbar">
        @include('core::admin._button-select')
        @include('core::admin._button-actions', ['limit' => ['delete']])
    </div>

    <div class="table-responsive">

        <table st-persist="tagsTable" st-table="displayedModels" st-safe-src="models" st-order st-filter class="table table-main">
            <thead>
                <tr>
                    <th class="delete"></th>
                    <th class="edit"></th>
                    <th st-sort="tag" class="tag st-sort">{{ __('Tag') }}</th>
                    <th st-sort="uses" st-sort-default="reverse" class="uses st-sort">{{ __('Uses') }}</th>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>
                        <input st-search="tag" class="form-control form-control-sm" placeholder="@lang('Filter')…" type="text">
                    </td>
                    <td></td>
                </tr>
            </thead>

            <tbody>
                <tr ng-repeat="model in displayedModels">
                    <td>
                        <input type="checkbox" checklist-model="checked.models" checklist-value="model">
                    </td>
                    <td>
                        @include('core::admin._button-edit', ['module' => 'tags'])
                    </td>
                    <td>@{{ model.tag }}</td>
                    <td>@{{ model.uses }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" typi-pagination></td>
                </tr>
            </tfoot>
        </table>

    </div>

</div>

@endsection
