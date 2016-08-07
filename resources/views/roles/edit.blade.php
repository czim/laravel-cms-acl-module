@extends(cms_config('views.layout'))

@section('title', 'ACL - Edit Role: ' . $role->getSlug())


@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ cms_route(\Czim\CmsCore\Support\Enums\NamedRoute::HOME) }}">Home</a></li>
        <li><a href="{{ cms_route('acl.roles.index') }}">ACL: Role list</a></li>
        <li class="active">Edit: {{ $role->getSlug() }}</li>
    </ol>
@endsection


@section('content')

    <div class="page-header">
        <h1>Edit Role: {{ $role->getSlug() }}</h1>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <form method="post" action="{{ cms_route('acl.roles.update', [ $role->getSlug() ]) }}">
                {{ method_field('put') }}
                {{ csrf_field() }}

                <div class="form-group">
                    <label>Key</label>
                    <p class="form-control-static">{{ $role->getSlug() }}</p>
                </div>
                <div class="form-group">
                    <label for="input-name">Name</label>
                    <input name="name" type="text" class="form-control" id="input-name" value="{{ old('name') ?: $role->name }}">
                </div>

                @if (isset($permissions) && count($permissions))

                    <div class="form-group">
                        <label for="input-permissions">Permissions</label>
                        <select multiple name="permissions[]" class="form-control" id="input-permissions">

                            @foreach ($permissions as $permission)
                                <option value="{{ $permission }}" @if (in_array($permission, $role->getAllPermissions())) selected="selected" @endif>
                                    {{ $permission }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                @endif

                <div class="btn-group pull-right">
                    <button type="submit" class="btn btn-success">Edit Role</button>
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
