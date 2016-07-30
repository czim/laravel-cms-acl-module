@extends(cms_config('views.layout'))

@section('title', 'ACL - Role: ' . $role->key)


@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ cms_route(\Czim\CmsCore\Support\Enums\NamedRoute::HOME) }}">Home</a></li>
        <li><a href="{{ cms_route('acl.roles.index') }}">ACL: Role list</a></li>
        <li class="active">{{ $role->key }}</li>
    </ol>
@endsection


@section('content')

    <div class="page-header">
        <h1>Role: {{ $role->key }}</h1>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <table class="table">
                <tr>
                    <th>Key</th>
                    <td>{{ $role->key }}</td>
                </tr>

                {{--<tr>--}}
                    {{--<th>Name</th>--}}
                    {{--<td>{{ $role->name }}</td>--}}
                {{--</tr>--}}

                <tr>
                    <th>Permissions</th>
                    <td>{{ implode(', ', $role->permissions) }}</td>
                </tr>

            </table>

            @if (cms_auth()->can('acl.roles.edit'))
                <a class="btn btn-primary" href="{{ cms_route('acl.roles.edit', [ $role->key ]) }}" role="button">
                    Edit
                </a>
            @endif

        </div>
    </div>
@endsection
