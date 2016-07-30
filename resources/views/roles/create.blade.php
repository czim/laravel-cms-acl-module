@extends(cms_config('views.layout'))

@section('title', 'ACL - New Role')


@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ cms_route(\Czim\CmsCore\Support\Enums\NamedRoute::HOME) }}">Home</a></li>
        <li><a href="{{ cms_route('acl.roles.index') }}">ACL: Role list</a></li>
        <li class="active">New Role</li>
    </ol>
@endsection


@section('content')

    <div class="page-header">
        <h1>New Role</h1>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <form method="post" action="{{ cms_route('acl.roles.store') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="input-key">Key</label>
                    <input name="key" type="text" class="form-control" id="input-key" placeholder="Key" value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label for="input-name">Name</label>
                    <input name="name" type="text" class="form-control" id="input-name" placeholder="Name" value="{{ old('name') }}">
                </div>

                @if (isset($permissions) && count($permissions))

                    <div class="form-group">
                        <label for="input-permissions">Permissions</label>
                        <select multiple name="permissions[]" class="form-control" id="input-permissions">

                            @foreach ($permissions as $permission)
                                <option value="{{ $permission }}">
                                    {{ $permission }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                @endif

                <div class="btn-group pull-right">
                    <button type="submit" class="btn btn-success">Create New Role</button>
                </div>

            </form>

        </div>
    </div>
@endsection
