@extends(cms_config('views.layout'))

@section('title', 'ACL - User: ' . $user->email)


@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ cms_route(\Czim\CmsCore\Support\Enums\NamedRoute::HOME) }}">Home</a></li>
        <li><a href="{{ cms_route('acl.users.index') }}">ACL: User list</a></li>
        <li class="active">{{ $user->email }}</li>
    </ol>
@endsection


@section('content')

    <div class="page-header">
        <h1>User: {{ $user->email }} <small>#{{ $user->id }}</small></h1>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

                <table class="table">
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>

                    <tr>
                        <th>Name</th>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                    </tr>

                    <tr>
                        <th>Roles</th>
                        <td>{{ implode(', ', $user->all_roles) }}</td>
                    </tr>

                </table>

                @if (cms_auth()->can('acl.users.edit'))
                    <a class="btn btn-primary" href="{{ cms_route('acl.users.edit', [ $user->id ]) }}" role="button">
                        Edit
                    </a>
                @endif

        </div>
    </div>
@endsection
