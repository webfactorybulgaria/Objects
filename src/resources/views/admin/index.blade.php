@extends('core::admin.master')

@section('title', trans('objects::global.name'))

@section('main')

<div ng-app="typicms" ng-cloak ng-controller="ListController">

    @include('core::admin._button-create', ['module' => 'objects'])

    <h1>
        <span>@{{ totalModels }} @choice('objects::global.objects', 2)</span>
    </h1>

    <div class="btn-toolbar">
        @include('core::admin._lang-switcher')
    </div>

    <div class="table-responsive">

        <table st-persist="objectsTable" st-table="displayedModels" st-order st-pipe="callServer" st-filter class="table table-condensed table-main">
            <thead>
                <tr>
                    <td colspan="5" st-items-by-page="itemsByPage" st-pagination="" st-template="/views/partials/pagination.custom.html"></td>
                </tr>
                <tr>
                    <th class="delete"></th>
                    <th class="edit"></th>
                    <th st-sort="status" class="status st-sort">Status</th>
                    <th st-sort="image" class="image st-sort">Image</th>
                    <th st-sort="title" class="title st-sort">Title</th>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>
                        <select class="form-control" st-input-event="change keydown" st-search="status.boolean">
                            <option value=""></option>
                            <option value="true">Active</option>
                            <option value="false">Not Active</option>
                        </select>
                    </td>
                    <td></td>
                    <td>
                        <input st-search="title" class="form-control input-sm" placeholder="@lang('global.Search')…" type="text">
                    </td>
                </tr>
            </thead>

            <tbody>
                <tr ng-repeat="model in displayedModels">
                    <td typi-btn-delete action="delete(model)"></td>
                    <td>
                        @include('core::admin._button-edit', ['module' => 'objects'])
                    </td>
                    <td typi-btn-status action="toggleStatus(model)" model="model"></td>
                    <td>
                        <img ng-src="@{{ model.thumb }}" alt="">
                    </td>
                    <td>@{{ model.title }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" st-items-by-page="itemsByPage" st-pagination="" st-template="/views/partials/pagination.custom.html"></td>
                    <td>
                        <div ng-include="'/views/partials/pagination.itemsPerPage.html'"></div>
                    </td>
                </tr>
            </tfoot>
        </table>

    </div>

</div>

@endsection
