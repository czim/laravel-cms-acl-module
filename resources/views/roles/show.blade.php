@extends(cms_config('views.layout'))

<?php $title = ucfirst(cms_trans('acl.roles.single')) . ': ' . $role->key; ?>

@section('title', $title)


@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ cms_route(\Czim\CmsCore\Support\Enums\NamedRoute::HOME) }}">
                {{ ucfirst(cms_trans('common.home')) }}
            </a></li>
        <li>
            <a href="{{ cms_route('acl.roles.index') }}">{{ cms_trans('acl.roles.index.title') }}</a>
        </li>
        <li class="active">
            {{ $title }}
        </li>
    </ol>
@endsection


@section('content')

    <div class="page-header">
        <h1>{{ $title }}</h1>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <table class="table">
                <tr>
                    <th>{{ cms_trans('acl.roles.form.key') }}</th>
                    <td>{{ $role->key }}</td>
                </tr>

                {{--<tr>--}}
                    {{--<th>{{ cms_trans('acl.roles.form.name') }}</th>--}}
                    {{--<td>{{ $role->name }}</td>--}}
                {{--</tr>--}}

                <tr>
                    <th>{{ cms_trans('acl.roles.form.permissions') }}</th>
                    <td>{{ implode(', ', $role->permissions) }}</td>
                </tr>

            </table>

            @if (cms_auth()->can('acl.roles.edit'))
                <a class="btn btn-primary" href="{{ cms_route('acl.roles.edit', [ $role->key ]) }}" role="button">
                    {{ ucfirst(cms_trans('common.action.edit')) }}
                </a>
            @endif

        </div>
    </div>
@endsection
