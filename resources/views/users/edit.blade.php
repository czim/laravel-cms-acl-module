@extends(cms_config('views.layout'))

@if ($create)
    <?php
        $title      = ucfirst(cms_trans('models.button.new-record', [ 'name' => cms_trans('acl.users.single') ]));
        $formAction = cms_route('acl.users.store')
    ?>
@else
    <?php
        $title      = ucfirst(cms_trans('common.action.edit')) . ': ' . $user->email;
        $formAction = cms_route('acl.users.update', [ $user->id ])
    ?>
@endif

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
        <h1>
            {{ $title }}
            @if ( ! $create) <small>#{{ $user->id }}</small> @endif
        </h1>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <form method="post" class="model-form" action="{{ $formAction }}">
                @if ( ! $create)
                    {{ method_field('put') }}
                @endif
                {{ csrf_field() }}

                <div class="form-group row">
                    <label class="control-label col-sm-2 required" for="input-email">
                        {{ cms_trans('acl.users.form.email') }}
                    </label>
                    <div class="col-sm-10">
                        @if ($create)
                            <input name="email" type="email" class="form-control" id="input-email" value="{{ old('email') }}" required="required">
                        @else
                            <p class="form-control-static">{{ $user->email }}</p>
                        @endif
                    </div>
                </div>

                @if ($create)
                    <div class="form-group row">
                        <label class="control-label col-sm-2 required" for="input-password">
                            {{ cms_trans('acl.users.form.password') }}
                        </label>
                        <div class="col-sm-10">
                            <input name="password" type="password" class="form-control" id="input-password" required="required">
                        </div>
                    </div>
                @else
                    <div class="form-group row">
                        <label class="control-label col-sm-2" for="input-password">
                            {{ cms_trans('acl.users.form.password-new') }}
                        </label>
                        <div class="col-sm-10">
                            <input name="password" type="password" class="form-control" id="input-password">
                        </div>
                    </div>
                @endif

                <div class="form-group row">
                    <label class="control-label col-sm-2" for="input-first-name">
                        {{ cms_trans('acl.users.form.first-name') }}
                    </label>
                    <div class="col-sm-10">
                        <input name="first_name" type="text" class="form-control" id="input-first-name" value="{{ old('first_name', $create ? null : $user->first_name) }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="control-label col-sm-2" for="input-last-name">
                        {{ cms_trans('acl.users.form.last-name') }}
                    </label>
                    <div class="col-sm-10">
                        <input name="last_name" type="text" class="form-control" id="input-last-name" value="{{ old('last_name', $create ? null : $user->last_name) }}">
                    </div>
                </div>

                @if (isset($roles) && count($roles))

                    <?php $currentRoles = $create ? [] : $user->all_roles; ?>

                    <div class="form-group row">

                        <label class="control-label col-sm-2" for="input-roles">
                            {{ cms_trans('acl.users.form.roles') }}
                        </label>


                        <div class="col-sm-10 multiselect-form-field">

                            <div class="left-panel">

                                <div class="panel-header">
                                    <b>{{ cms_trans('acl.users.form.available-roles') }}</b>
                                </div>

                                <select name="ignore[]" id="input-roles" class="form-control" size="8" multiple="multiple">

                                    @foreach (array_diff($roles, $currentRoles) as $role)
                                        <option value="{{ $role }}">
                                            {{ ucfirst(snake_case($role, ' ')) }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="buttons-panel">

                                <div class="panel-header">&nbsp;</div>

                                <button type="button" id="input-roles_rightAll" class="btn btn-block btn-primary"
                                        title="{{ cms_trans('acl.users.form.select-all-roles') }}">
                                    <i class="glyphicon glyphicon-forward"></i>
                                </button>
                                <button type="button" id="input-roles_rightSelected" class="btn btn-block btn-primary"
                                        title="{{ cms_trans('acl.users.form.select-roles') }}">
                                    <i class="glyphicon glyphicon-chevron-right"></i>
                                </button>
                                <button type="button" id="input-roles_leftSelected" class="btn btn-block btn-default"
                                        title="{{ cms_trans('acl.users.form.deselect-all-roles') }}">
                                    <i class="glyphicon glyphicon-chevron-left"></i>
                                </button>
                                <button type="button" id="input-roles_leftAll" class="btn btn-block btn-default"
                                        title="{{ cms_trans('acl.users.form.deselect-roles') }}">
                                    <i class="glyphicon glyphicon-backward"></i>
                                </button>
                            </div>

                            <div class="right-panel">

                                <div class="panel-header">
                                    <b>{{ cms_trans('acl.users.form.current-roles') }}</b>
                                </div>

                                <select name="roles[]" id="input-roles_to" class="form-control" size="8" multiple="multiple">

                                    @foreach ($currentRoles as $role)
                                        <option value="{{ $role }}">
                                            {{ ucfirst(snake_case($role, ' ')) }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
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

@push('javascript-end')
    <script>
        $(function() {
            $('#input-roles').multiselect({
                'keepRenderingSort': true,
                'submitAllLeft': false,
                'submitAllRight': true
            });
        });
    </script>
@endpush
