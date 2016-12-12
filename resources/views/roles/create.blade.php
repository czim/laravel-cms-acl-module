@extends(cms_config('views.layout'))

<?php $title = ucfirst(cms_trans('models.button.new-record', [ 'name' => cms_trans('acl.roles.single') ])); ?>

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

            <form method="post" action="{{ cms_route('acl.roles.store') }}">
                {{ csrf_field() }}


                <div class="form-group row">
                    <label class="control-label col-sm-2 required" for="input-key">
                        {{ cms_trans('acl.roles.form.key') }}
                    </label>
                    <div class="col-sm-10">
                        <input name="key" type="text" class="form-control" id="input-key" value="{{ old('email') }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="control-label col-sm-2 required" for="input-name">
                        {{ cms_trans('acl.roles.form.name') }}
                    </label>
                    <div class="col-sm-10">
                        <input name="name" type="text" class="form-control" id="input-name" value="{{ old('name') }}">
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
                                    <option value="{{ $permission }}">
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
                                {{ ucfirst(cms_trans('common.action.create')) }}
                            </button>
                        </div>

                    </div>
                </div>

            </form>

        </div>
    </div>
@endsection
