@extends(cms_config('views.layout'))

<?php $title = ucfirst(cms_trans('acl.users.single')) . ': ' . $user->email; ?>

@section('title', $title)


@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ cms_route(\Czim\CmsCore\Support\Enums\NamedRoute::HOME) }}">
                {{ ucfirst(cms_trans('common.home')) }}
            </a></li>
        <li>
            <a href="{{ cms_route('acl.users.index') }}">{{ cms_trans('acl.users.index.title') }}</a>
        </li>
        <li class="active">
            {{ $title }}
        </li>
    </ol>
@endsection


@section('content')

    <div class="page-header">
        <h1>{{ $title }} <small>#{{ $user->id }}</small></h1>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

                <table class="table">
                    <tr>
                        <th>{{ cms_trans('acl.users.form.email') }}</th>
                        <td>{{ $user->email }}</td>
                    </tr>

                    <tr>
                        <th>{{ cms_trans('acl.users.form.name') }}</th>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                    </tr>

                    <tr>
                        <th>{{ cms_trans('acl.users.form.roles') }}</th>
                        <td>{{ implode(', ', $user->all_roles) }}</td>
                    </tr>

                </table>

                @if (cms_auth()->can('acl.users.edit'))
                    <a class="btn btn-primary" href="{{ cms_route('acl.users.edit', [ $user->id ]) }}" role="button">
                        {{ ucfirst(cms_trans('common.action.edit')) . ' ' . cms_trans('acl.users.single') }}
                    </a>
                @endif

        </div>
    </div>
@endsection
