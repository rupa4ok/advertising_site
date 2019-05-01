@extends('layouts.app')

@section('content')
    @include('admin.users._nav')

    <div class="d-flex flex-row mb-3 mt-3">
        <a href="{{ route('admin.users.create') }}" class="btn btn-success mr-1">Add user</a>
    </div>
    
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>NAME</th>
            <th>EMAIL</th>
            <th>STATUS</th>
            <th>ROLE</th>
        </tr>
        </thead>
        <tbody>
        
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td><a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a></td>
                <td>{{ $user->email }}</td>
                <td>
                    @if ($user->status === \App\Models\User::STATUS_WAIT)
                        <span class="badge badge-secondary">Waiting</span>
                    @endif
                    @if ($user->status === \App\Models\User::STATUS_ACTIVE)
                        <span class="badge badge-primary">Active</span>
                    @endif
                </td>
                <td>{{ $user->role }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
