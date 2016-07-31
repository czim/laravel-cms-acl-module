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
                        <th>&nbsp;</th>
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
                            <td>
                                @if (cms_auth()->can('acl.roles.delete') && ! cms_auth()->roleInUse($role->key))
                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a class="btn btn-danger delete-record-action" href="#" role="button"
                                           data-id="{{$role->key}}"
                                           data-toggle="modal" data-target="#delete-role-modal"
                                        >delete</a>
                                    </div>
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


    <div id="delete-role-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title delete-modal-title">Delete Role</h4>
                </div>
                <div class="modal-body">
                    <p class="text-danger">This action cannot be undone!</p>
                </div>
                <div class="modal-footer">
                    <form class="delete-modal-form" method="post" data-url="{{ cms_route('acl.roles.destroy', [ 'IDHERE' ]) }}" action="">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}

                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger delete-modal-button">Delete Role</button>
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
        $('.delete-modal-title').text('Delete Role: ' + $(this).attr('data-id'));
    });
</script>
@endpush
