@extends(cms_config('views.layout'))

@if ($create)
    <?php
        $title      = ucfirst(cms_trans('models.button.new-record', [ 'name' => cms_trans('acl.roles.single') ]));
        $formAction = cms_route('acl.roles.store')
    ?>
@else
    <?php
        $title      = ucfirst(cms_trans('common.action.edit')) . ' ' .  cms_trans('acl.roles.single') . ': ' . $role->getSlug();
        $formAction = cms_route('acl.roles.update', [ $role->getSlug() ])
    ?>
@endif


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

            <form method="post" class="model-form" action="{{ $formAction }}">
                @if ( ! $create)
                    {{ method_field('put') }}
                @endif
                {{ csrf_field() }}

                <div class="form-group row @if ($errors->has('key')) has-error @endif">
                    <label class="control-label col-sm-2 required" @if ( ! $create) for="input-key" @endif>
                        {{ cms_trans('acl.roles.form.key') }}
                    </label>
                    <div class="col-sm-10">
                        @if ($create)
                            <input name="key" type="text" class="form-control" id="input-key" value="{{ old('key') }}" required="required">
                        @else
                            <p class="form-control-static">{{ $role->getSlug() }}</p>
                        @endif
                    </div>
                </div>

                <div class="form-group row @if ($errors->has('name')) has-error @endif">
                    <label class="control-label col-sm-2 required" for="input-name">
                        {{ cms_trans('acl.roles.form.name') }}
                    </label>
                    <div class="col-sm-10">
                        <input name="name" type="text" class="form-control" id="input-name" value="{{ old('name', $create ? null : $role->name) }}" required="required">
                    </div>
                </div>


                @if (isset($permissions) && count($permissions))

                    <?php $currentPermissions = $create ? [] : $role->getAllPermissions(); ?>

                    <div class="form-group row @if ($errors->has('permissions')) has-error @endif">

                        <label class="control-label col-sm-2" for="input-permissions">
                            {{ cms_trans('acl.roles.form.permissions') }}
                        </label>

                        <div class="col-sm-10 multiselect-form-field">

                            <div class="left-panel">

                                <div class="panel-header">
                                    <b>{{ cms_trans('acl.roles.form.available-permissions') }}</b>
                                </div>

                                <select name="ignore[]" id="input-permissions" class="form-control" size="20" multiple="multiple">

                                    @foreach (array_diff($permissions, $currentPermissions) as $permission)
                                        <option value="{{ $permission }}">
                                            {{ $permission }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="buttons-panel">

                                <div class="panel-header">&nbsp;</div>

                                <button type="button" id="input-permissions_rightAll" class="btn btn-block btn-primary"
                                        title="{{ cms_trans('acl.roles.form.select-all-permissions') }}">
                                    <i class="glyphicon glyphicon-forward"></i>
                                </button>
                                <button type="button" id="input-permissions_rightSelected" class="btn btn-block btn-primary"
                                        title="{{ cms_trans('acl.roles.form.select-permissions') }}">
                                    <i class="glyphicon glyphicon-chevron-right"></i>
                                </button>
                                <button type="button" id="input-permissions_leftSelected" class="btn btn-block btn-default"
                                        title="{{ cms_trans('acl.roles.form.deselect-all-permissions') }}">
                                    <i class="glyphicon glyphicon-chevron-left"></i>
                                </button>
                                <button type="button" id="input-permissions_leftAll" class="btn btn-block btn-default"
                                        title="{{ cms_trans('acl.roles.form.deselect-permissions') }}">
                                    <i class="glyphicon glyphicon-backward"></i>
                                </button>
                            </div>

                            <div class="right-panel">

                                <div class="panel-header">
                                    <b>{{ cms_trans('acl.roles.form.current-permissions') }}</b>
                                </div>

                                <select name="permissions[]" id="input-permissions_to" class="form-control" size="20" multiple="multiple">

                                    @foreach ($currentPermissions as $permission)
                                        <option value="{{ $permission }}">
                                            {{ $permission }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
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

                                @if ($create)
                                    {{ ucfirst(cms_trans('common.action.create')) }}
                                @else
                                    {{ ucfirst(cms_trans('common.action.save')) }}
                                @endif
                            </button>
                        </div>

                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection


@push('javascript-end')
    <script>
        $(function() {
            $('#input-permissions').multiselect({
                'keepRenderingSort': true,
                'submitAllLeft': false,
                'submitAllRight': true
            });
        });
    </script>
@endpush
