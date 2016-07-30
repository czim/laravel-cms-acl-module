@extends(cms_config('views.layout'))

@section('title', 'ACL - Roles')


@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ cms_route(\Czim\CmsCore\Support\Enums\NamedRoute::HOME) }}">Home</a></li>
        <li class="active">ACL: Role list</li>
    </ol>
@endsection


@section('content')

    <div class="page-header">

        <div class="btn-toolbar pull-right">
            <div class="btn-group">
                @if (cms_auth()->can('acl.roles.create'))
                    <a href="{{ cms_route('acl.roles.create') }}" class="btn btn-primary">New Role</a>
                @endif
            </div>
        </div>

        <h1>Role list</h1>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <table class="table ">

                <thead>
                    <tr>
                        <th>Key</th>
                        <th>In use</th>
                        <th>Permissions</th>
                    </tr>
                </thead>

                <?php
                    // set the user link route according to permissions
                    $route = cms_auth()->can('acl.roles.edit') ? 'acl.roles.edit' : 'acl.roles.show';
                ?>

                <tbody>
                    @forelse ($roles as $role)

                        <tr>
                            <td class="col-primary">
                                <a href="{{ cms_route($route, [ $role->key ]) }}">
                                    {{ $role->key }}
                                </a>
                            </td>
                            <td>
                                @if (cms_auth()->roleInUse($role->key))
                                    <span class="text-success">Yes</span>
                                @else
                                    <span class="text-muted">No</span>
                                @endif
                            </td>
                            <td>
                                @if ($role->permissions && count($role->permissions))
                                    {{ implode(', ', $role->permissions) }}
                                @endif
                            </td>
                        </tr>

                    @empty
                        <em>No roles</em>
                    @endforelse

                </tbody>
            </table>

        </div>
    </div>

@endsection
