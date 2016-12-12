@extends(cms_config('views.layout'))

<?php $title = ucfirst(cms_trans('common.action.edit')) . ' ' .  cms_trans('acl.roles.single') . ': ' . $role->getSlug(); ?>

@section('title', $title)


@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ cms_route(\Czim\CmsCore\Support\Enums\NamedRoute::HOME) }}">
                {{ ucfirst(cms_trans('common.home')) }}
            </a></li>
        <li>
            <a href="{{ cms_route('acl.roles.index') }}">{{ cms_trans('acl.roles.index.title') }}</a>
        </li>
        <li class="active">
            {{ $title }}
        </li>
    </ol>
@endsection


@section('content')

    <div class="page-header">
        <h1>{{ $title }}</h1>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <form method="post" class="model-form" action="{{ cms_route('acl.roles.update', [ $role->getSlug() ]) }}">
                {{ method_field('put') }}
                {{ csrf_field() }}

                <div class="form-group row">
                    <label class="control-label col-sm-2 required">
                        {{ cms_trans('acl.roles.form.key') }}
                    </label>
                    <div class="col-sm-10">
                        <p class="form-control-static">{{ $role->getSlug() }}</p>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="control-label col-sm-2 required" for="input-name">
                        {{ cms_trans('acl.roles.form.name') }}
                    </label>
                    <div class="col-sm-10">
                        <input name="name" type="text" class="form-control" id="input-name" value="{{ old('name', $role->name) }}">
                    </div>
                </div>


                @if (isset($permissions) && count($permissions))

                    <div class="form-group row">
                        <label class="control-label col-sm-2" for="input-permissions">
                            {{ cms_trans('acl.roles.form.permissions') }}
                        </label>
                        <div class="col-sm-10">
                            <select multiple name="permissions[]" class="form-control" id="input-permissions">

                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission }}" @if (in_array($permission, $role->getAllPermissions())) selected="selected" @endif>
                                        {{ $permission }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                @endif


                <div class="form-group edit-button-row clearfix">

                    <div class="col-sm-4">
                        <a href="{{ cms_route('acl.roles.index') }}" class="btn btn-default edit-button-cancel">
                            <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>
                            {{ ucfirst(cms_trans('common.buttons.cancel')) }}
                        </a>
                    </div>

                    <div class="col-sm-8">

                        <div class="btn-group pull-right" role="group" aria-label="save">
                            <button type="submit" class="btn btn-success edit-button-save">
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                {{ ucfirst(cms_trans('common.action.save')) }}
                            </button>
                        </div>

                    </div>
                </div>

            </form>


            @if ( ! cms_auth()->roleInUse($role->getSlug()))

            <form method="post" action="{{ cms_route('acl.roles.destroy', [ $role->getSlug() ]) }}">
                {{ method_field('delete') }}
                {{ csrf_field() }}

                <button type="submit" class="btn btn-danger">Delete Role</button>
            </form>

            @endif

        </div>
    </div>
@endsection
