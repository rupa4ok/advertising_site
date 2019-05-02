@extends('layouts.app')

@section('content')
    @include('admin.regions._nav')
    
    <div class="d-flex flex-row mb-3 mt-3">
        <a href="{{ route('admin.regions.edit', $regions) }}" class="btn btn-primary mr-1">Edit</a>
            <form method="POST" action="{{ route('admin.regions.update', $regions) }}" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
    </div>
    
    <table class="table table-bordered table-striped">
        <tbody>
        <tr>
            <th>ID</th>
            <td>{{ $regions->id }}</td>
        </tr>
        <tr>
            <th>NAME</th>
            <td>{{ $regions->name }}</td>
        </tr>
        <tr>
            <th>SLUG</th>
            <td>{{ $regions->email }}</td>
        </tr>
        </tbody>
    </table>
@endsection
