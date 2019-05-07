@extends('layouts.app')

@section('content')
    <div class="container">
        <ul class="nav nav-tabs md-3">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.home') }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.users.index') }}">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.regions.index') }}">Regions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.adverts.categories.index') }}">Category</a>
            </li>
        </ul>
        
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Dashboard</div>
                    
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        
                        LFi,jhl
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
