@extends(cms_config('views.layout'))

<?php $title = ucfirst(cms_trans('common.action.edit')) . ': ' . $user->email; ?>

@section('title', $title)


@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ cms_route(\Czim\CmsCore\Support\Enums\NamedRoute::HOME) }}">
                {{ ucfirst(cms_trans('common.home')) }}
        </a></li>
        <li>
            <a href="{{ cms_route('acl.users.index') }}">{{ cms_trans('acl.users.index.title') }}</a>
        </li>
        <li class="active">
            {{ $title }}
        </li>
    </ol>
@endsection


@section('content')

    <div class="page-header">
        <h1>{{ $title }} <small>#{{ $user->id }}</small></h1>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <form method="post" class="model-form" action="{{ cms_route('acl.users.update', [ $user->id ]) }}">
                {{ method_field('put') }}
                {{ csrf_field() }}

                <div class="form-group row">
                    <label class="control-label col-sm-2 required" for="input-email">
                        {{ cms_trans('acl.users.form.email') }}
                    </label>
                    <div class="col-sm-10">
                        <p class="form-control-static">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="control-label col-sm-2" for="input-password">
                        {{ cms_trans('acl.users.form.password-new') }}
                    </label>
                    <div class="col-sm-10">
                        <input name="password" type="password" class="form-control" id="input-password">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="control-label col-sm-2" for="input-first-name">
                        {{ cms_trans('acl.users.form.first-name') }}
                    </label>
                    <div class="col-sm-10">
                        <input name="first_name" type="text" class="form-control" id="input-first-name" value="{{ old('first_name') ?: $user->first_name }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="control-label col-sm-2" for="input-last-name">
                        {{ cms_trans('acl.users.form.last-name') }}
                    </label>
                    <div class="col-sm-10">
                        <input name="last_name" type="text" class="form-control" id="input-last-name" value="{{ old('last_name') ?: $user->last_name }}">
                    </div>
                </div>

                @if (isset($roles) && count($roles))

                    <div class="form-group row">
                        <label class="control-label col-sm-2" for="input-roles">
                            {{ cms_trans('acl.users.form.roles') }}
                        </label>
                        <div class="col-sm-10">
                            <select multiple name="roles[]" class="form-control" id="input-last-name">

                                @foreach ($roles as $role)
                                    <option value="{{ $role }}" @if (in_array($role, $user->all_roles)) selected="selected" @endif>
                                        {{ ucfirst(snake_case($role, ' ')) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                @endif


                <div class="form-group edit-button-row clearfix">

                    <div class="col-sm-4">
                        <a href="{{ cms_route('acl.users.index') }}" class="btn btn-default edit-button-cancel">
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

        </div>
    </div>
@endsection
