@extends(cms_config('views.layout'))

@section('title', 'ACL - New User')


@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ cms_route(\Czim\CmsCore\Support\Enums\NamedRoute::HOME) }}">Home</a></li>
        <li><a href="{{ cms_route('acl.users.index') }}">ACL: User list</a></li>
        <li class="active">New User</li>
    </ol>
@endsection


@section('content')

    <div class="page-header">
        <h1>New User</h1>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <form method="post" action="{{ cms_route('acl.users.store') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="input-email">Email address</label>
                    <input name="email" type="email" class="form-control" id="input-email" placeholder="Email" value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label for="input-password">Password</label>
                    <input name="password" type="password" class="form-control" id="input-password" placeholder="Password">
                </div>
                <div class="form-group">
                    <label for="input-first-name">First name</label>
                    <input name="first_name" type="text" class="form-control" id="input-first-name" value="{{ old('first_name') }}">
                </div>
                <div class="form-group">
                    <label for="input-last-name">Last name</label>
                    <input name="last_name" type="text" class="form-control" id="input-last-name" value="{{ old('last_name') }}">
                </div>

                @if (isset($roles) && count($roles))

                    <div class="form-group">
                        <label for="input-roles">Roles</label>
                        <select multiple name="roles[]" class="form-control" id="input-roles">

                            @foreach ($roles as $role)
                                <option value="{{ $role }}">
                                    {{ ucfirst(snake_case($role, ' ')) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                @endif

                <div class="btn-group pull-right">
                    <button type="submit" class="btn btn-success">Create New User</button>
                </div>

            </form>

        </div>
    </div>
@endsection
