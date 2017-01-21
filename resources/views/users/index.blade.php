@extends(cms_config('views.layout'))

<?php $title = cms_trans('acl.users.index.title'); ?>

@section('title', $title)


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
                @if (cms_auth()->can('acl.users.create'))
                    <a href="{{ cms_route('acl.users.create') }}" class="btn btn-primary">
                        {{ ucfirst(cms_trans('models.button.new-record', [ 'name' => cms_trans('acl.users.single') ])) }}
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
                        <th class="column primary-id column-right">{{ ucfirst(cms_trans('common.attributes.id')) }}</th>
                        <th class="column">{{ cms_trans('acl.users.columns.email') }}</th>
                        <th class="column">{{ cms_trans('acl.users.columns.roles') }}</th>
                        <th class="column column-center">{{ ucfirst(cms_trans('common.attributes.created-at')) }}</th>
                        <th class="column column-center">{{ ucfirst(cms_trans('common.attributes.updated-at')) }}</th>
                        <th class="column">&nbsp;</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        // set the user link route according to permissions
                        $route = cms_auth()->can('acl.users.edit') ? 'acl.users.edit' : 'acl.users.show';
                    ?>

                    @forelse ($users as $user)

                        <tr class="records-row" default-action-url="{{ cms_route($route, [ $user->id ]) }}">
                            <td class="column primary-id column-right default-action">
                                {{ $user->id }}
                            </td>
                            <td class="column default-action">
                                <a href="{{ cms_route($route, [ $user->id ]) }}">
                                    {{ $user->getUsername() }}
                                </a>
                            </td>
                            <td class="column default-action">
                                @if ($user->all_roles && count($user->all_roles))
                                    {{ implode(', ', $user->all_roles) }}
                                    &nbsp;
                                @endif

                                @if ($user->isAdmin())
                                    <span class="label label-primary">{{ cms_trans('common.admin') }}</span>
                                @endif
                            </td>
                            <td class="column column-center small default-action">
                                @if ($user->created_at)
                                    {{ $user->created_at->format('Y-m-d H:i') }}
                                @endif
                            </td>
                            <td class="column column-center small default-action">
                                @if ($user->updated_at)
                                    {{ $user->updated_at->format('Y-m-d H:i') }}
                                @endif
                            </td>
                            <td>
                                @if (cms_auth()->canAnyOf(['acl.users.edit', 'acl.users.delete']))
                                    <div class="btn-group btn-group-xs record-actions pull-right tr-show-on-hover" role="group">

                                        @if (cms_auth()->can('acl.users.edit'))
                                            <a class="btn btn-default edit-record-action" href="{{ cms_route($route, [ $user->id ]) }}" role="button"
                                               title="{{ ucfirst(cms_trans('common.action.edit')) }}"
                                            ><i class="fa fa-edit"></i></a>
                                        @endif

                                        @if (cms_auth()->can('acl.users.delete'))
                                            <a class="btn btn-danger delete-record-action" href="#" role="button"
                                               data-id="{{ $user->id }}"
                                               data-toggle="modal" data-target="#delete-user-modal"
                                               title="{{ ucfirst(cms_trans('common.action.delete')) }}"
                                            ><i class="fa fa-trash-o"></i></a>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>

                    @empty
                        <em>{{ cms_trans('acl.users.index.empty_list') }}</em>
                    @endforelse

                </tbody>
            </table>

        </div>
    </div>


    <div id="delete-user-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ ucfirst(cms_trans('common.action.close')) }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title delete-modal-title">
                        {{ ucfirst(cms_trans('models.button.delete-record', [ 'name' => cms_trans('acl.users.single') ])) }}
                    </h4>
                </div>
                <div class="modal-body">
                    <p class="text-danger">{{ cms_trans('common.cannot-undo') }}</p>
                </div>
                <div class="modal-footer">
                    <form class="delete-modal-form" method="post" data-url="{{ cms_route('acl.users.destroy', [ 'IDHERE' ]) }}" action="">
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


@push('javascript-end')
    <script>
        $(function() {
            $('.delete-record-action').click(function () {

                var form = $('.delete-modal-form');

                form.attr(
                    'action',
                    form.attr('data-url').replace('IDHERE', $(this).attr('data-id'))
                );

                $('.delete-modal-title').text(
                    "{{ ucfirst(cms_trans('models.button.delete-record', [ 'name' => cms_trans('acl.users.single') ])) }} #" + $(this).attr('data-id')
                );
            });

            @if (count($users))
                $('tr.records-row td.default-action').click(function () {
                    window.location.href = $(this).closest('tr').attr('default-action-url');
                });
            @endif
        });
    </script>
@endpush
