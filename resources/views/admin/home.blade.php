@extends('layouts.app')

@section('content')
    <ul class="nav nav-tabs md-3">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.home') }}">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.index') }}">Users</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admins.regions.index') }}">Regions</a>
        </li>
    </ul>
@endsection
