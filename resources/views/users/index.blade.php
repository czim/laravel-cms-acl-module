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
                        <th class="col-id">#</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th class="col-date">Created</th>
                        <th class="col-date">Updated</th>
                        <th>&nbsp;</th>
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
                            <td>
                                @if (cms_auth()->can('acl.users.delete'))
                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a class="btn btn-danger delete-record-action" href="#" role="button"
                                           data-id="{{$user->id}}"
                                           data-toggle="modal" data-target="#delete-user-modal"
                                        >delete</a>
                                    </div>
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


    <div id="delete-user-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title delete-modal-title">Delete User</h4>
                </div>
                <div class="modal-body">
                    <p class="text-danger">This action cannot be undone!</p>
                </div>
                <div class="modal-footer">
                    <form class="delete-modal-form" method="post" data-url="{{ cms_route('acl.users.destroy', [ 'IDHERE' ]) }}" action="">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}

                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger delete-modal-button">Delete User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('javascript-end')
    <script>
        $('.delete-record-action').click(function () {
            var form = $('.delete-modal-form');
            form.attr(
                'action',
                form.attr('data-url').replace('IDHERE', $(this).attr('data-id'))
            );
            $('.delete-modal-title').text('Delete User #' + $(this).attr('data-id'));
        });
    </script>
@endpush
