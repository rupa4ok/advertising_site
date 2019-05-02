@extends('layouts.app')

@section('content')
    @include('admin.users._nav')
    
    <div class="d-flex flex-row mb-3 mt-3">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary mr-1">Edit</a>
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
    </div>
    
    <table class="table table-bordered table-striped">
        <tbody>
        <tr>
            <th>ID</th>
            <td>{{ $user->id }}</td>
        </tr>
        <tr>
            <th>NAME</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>EMAIL</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>STATUS</th>
            <td>@if ($user->isWait())
                    <span class="badge badge-secondary">Waiting</span>
                @endif
                @if ($user->isActive())
                    <span class="badge badge-primary">Active</span>
                @endif</td>
        </tr>
        <tr>
            <th>ROLE</th>
            <td>
                @if ($user->isAdmin())
                    <span class="badge badge-danger">Admin</span>
                @else
                    <span class="badge badge-secondary">User</span>
                @endif
            </td>
        </tr>
        </tbody>
    </table>
@endsection
