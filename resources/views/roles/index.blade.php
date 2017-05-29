@extends(cms_config('views.layout'))

<?php $title = cms_trans('acl.roles.index.title'); ?>

@section('title', 'ACL - Roles')


@section('breadcrumbs')
    <ol class="breadcrumb">
        <li>
            <a href="{{ cms_route(\Czim\CmsCore\Support\Enums\NamedRoute::HOME) }}">
                {{ ucfirst(cms_trans('common.home')) }}
            </a>
        </li>
        <li class="active">
            {{ $title }}
        </li>
    </ol>
@endsection


@section('content')

    <div class="page-header">

        <div class="btn-toolbar pull-right">
            <div class="btn-group">
                @if (cms_auth()->can('acl.roles.create'))
                    <a href="{{ cms_route('acl.roles.create') }}" class="btn btn-primary">
                        {{ ucfirst(cms_trans('models.button.new-record', [ 'name' => cms_trans('acl.roles.single') ])) }}
                    </a>
                @endif
            </div>
        </div>

        <h1>{{ $title }}</h1>
    </div>

    <div class="row">
        <div>

            <table class="table table-striped table-hover records-table">

                <thead>
                    <tr>
                        <th class="column">{{ ucfirst(cms_trans('acl.roles.columns.key')) }}</th>
                        <th class="column">{{ ucfirst(cms_trans('acl.roles.columns.in-use')) }}</th>
                        <th class="column">{{ ucfirst(cms_trans('acl.roles.columns.permissions')) }}</th>
                        <th class="column">&nbsp;</th>
                    </tr>
                </thead>

                <?php
                    // set the role link route according to permissions
                    $route = cms_auth()->can('acl.roles.edit') ? 'acl.roles.edit' : 'acl.roles.show';
                ?>

                <tbody>
                    @forelse ($roles as $role)

                        <tr class="records-row" data-id="{{ $role->key }}" data-reference="{{ $role->key }}" data-default-action-url="{{ cms_route($route, [ $role->key ]) }}">
                            <td class="column default-action">
                                <a href="{{ cms_route($route, [ $role->key ]) }}">
                                    {{ $role->key }}
                                </a>
                            </td>
                            <td class="column column-center default-action">
                                @if (cms_auth()->roleInUse($role->key))
                                    <i class="fa fa-check text-success" title="{{ cms_trans('common.boolean.true') }}"></i>
                                @else
                                    <i class="fa fa-times text-danger" title="{{ cms_trans('common.boolean.false') }}"></i>
                                @endif
                            </td>
                            <td class="default-action">
                                @if ($role->permissions && count($role->permissions))
                                    @if (count($role->permissions) > 5)
                                        {{  count($role->permissions) }} {{ cms_trans('acl.permissions') }}
                                    @else
                                        {{ implode(', ', $role->permissions) }}
                                    @endif

                                @endif
                            </td>
                            <td>
                                @if (cms_auth()->canAnyOf(['acl.roles.edit', 'acl.roles.delete']))
                                    <div class="btn-group btn-group-xs record-actions pull-right tr-show-on-hover" role="group">

                                        @if (cms_auth()->can('acl.roles.edit'))
                                            <a class="btn btn-default edit-record-action" href="{{ cms_route($route, [ $role->key ]) }}" role="button"
                                               title="{{ ucfirst(cms_trans('common.action.edit')) }}"
                                            ><i class="fa fa-edit"></i></a>
                                        @endif

                                        @if (cms_auth()->can('acl.roles.delete') && ! cms_auth()->roleInUse($role->key))
                                            <a class="btn btn-danger delete-record-action" href="#" role="button"
                                               data-toggle="modal" data-target="#delete-role-modal"
                                               title="{{ ucfirst(cms_trans('common.action.delete')) }}"
                                            ><i class="fa fa-trash-o"></i></a>
                                        @endif
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ ucfirst(cms_trans('common.action.close')) }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title delete-modal-title">
                        {{ ucfirst(cms_trans('models.button.delete-record', [ 'name' => cms_trans('acl.roles.single') ])) }}
                    </h4>
                </div>
                <div class="modal-body">
                    <p class="text-danger">{{ cms_trans('common.cannot-undo') }}</p>
                </div>
                <div class="modal-footer">
                    <form class="delete-modal-form" method="post" data-url="{{ cms_route('acl.roles.destroy', [ 'IDHERE' ]) }}" action="">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}

                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ ucfirst(cms_trans('common.action.close')) }}
                        </button>
                        <button type="submit" class="btn btn-danger delete-modal-button">
                            {{ ucfirst(cms_trans('common.action.delete')) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


@cms_script
<script>
    $(function() {
        $('.delete-record-action').click(function () {

            var form      = $('.delete-modal-form'),
                row       = $(this).closest('tr');

            var id        = row.attr('data-id'),
                reference = row.attr('data-reference').trim();

            if ( ! reference || ! reference.length) {
                reference = '#' + id;
            }

            form.attr(
                'action',
                form.attr('data-url').replace('IDHERE', id)
            );

            $('.delete-modal-title').text(
                "{{ ucfirst(cms_trans('models.button.delete-record', [ 'name' => cms_trans('acl.roles.single') ])) }}: " + reference
            );
        });

        @if (count($roles))
            $('tr.records-row td.default-action').click(function () {
                window.location.href = $(this).closest('tr').attr('data-default-action-url');
            });
        @endif
    });
</script>
@cms_endscript
