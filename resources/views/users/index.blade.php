@extends(cms_config('views.layout'))

@section('title', 'ACL - Users')


@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ cms_route(\Czim\CmsCore\Support\Enums\NamedRoute::HOME) }}">Home</a></li>
        <li class="active">ACL: User list</li>
    </ol>
@endsection


@section('content')

    <div class="page-header">

        <div class="btn-toolbar pull-right">
            <div class="btn-group">
                @if (cms_auth()->can('acl.users.create'))
                    <a href="{{ cms_route('acl.users.create') }}" class="btn btn-primary">New User</a>
                @endif
            </div>
        </div>

        <h1>User list</h1>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <table class="table ">

                <thead>
                    <tr>
                        <th class="col-id">ID</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th class="col-date">Created</th>
                        <th class="col-date">Updated</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                            // set the user link route according to permissions
                            $route = cms_auth()->can('acl.users.edit') ? 'acl.users.edit' : 'acl.users.show';
                    ?>

                    @forelse ($users as $user)
                        <tr>
                            <td class="col-id">
                                {{ $user->id }}
                            </td>
                            <td class="col-primary">
                                <a href="{{ cms_route($route, [ $user->id ]) }}">
                                    {{ $user->getUsername() }}
                                </a>
                            </td>
                            <td>
                                @if ($user->all_roles && count($user->all_roles))
                                    {{ implode(', ', $user->all_roles) }}
                                    &nbsp;
                                @endif

                                @if ($user->isAdmin())
                                    <span class="label label-primary">admin</span>
                                @endif
                            </td>
                            <td class="col-date">
                                @if ($user->created_at)
                                    {{ $user->created_at->format('Y-m-d H:i') }}
                                @endif
                            </td>
                            <td class="col-date">
                                @if ($user->updated_at)
                                    {{ $user->updated_at->format('Y-m-d H:i') }}
                                @endif
                            </td>
                        </tr>

                    @empty
                        <em>No users</em>
                    @endforelse

                </tbody>
            </table>

        </div>
    </div>

@endsection
