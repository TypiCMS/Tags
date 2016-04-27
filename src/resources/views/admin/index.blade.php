@extends('core::admin.master')

@section('title', trans('tags::global.name'))

@section('main')

<div ng-app="typicms" ng-cloak ng-controller="ListController">

    @include('core::admin._button-create', ['module' => 'tags'])

    <h1>@lang('tags::global.name')</h1>

    <div class="table-responsive">

        <table st-persist="tagsTable" st-table="displayedModels" st-safe-src="models" st-order st-filter class="table table-condensed table-main">
            <thead>
                <tr>
                    <th class="delete"></th>
                    <th class="edit"></th>
                    <th st-sort="tag" class="tag st-sort">Tag</th>
                    <th st-sort="uses" st-sort-default="reverse" class="uses st-sort">Uses</th>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>
                        <input st-search="tag" class="form-control input-sm" placeholder="@lang('global.Search')…" type="text">
                    </td>
                    <td></td>
                </tr>
            </thead>

            <tbody>
                <tr ng-repeat="model in displayedModels">
                    <td typi-btn-delete action="delete(model, model.tag)"></td>
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
