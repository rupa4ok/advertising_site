@extends('layouts.app')

@section('content')
    @include('admin.regions._nav')

    <p><a href="{{ route('admin.regions.create') }}" class="btn btn-success mr-1 mt-3">Add region</a></p>
    
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>NAME</th>
            <th>SLUG</th>
            <th>PARENT</th>
        </tr>
        </thead>
        <tbody>
        
        @foreach($regions as $region)
            <tr>
                <td>{{ $region->id }}</td>
                <td><a href="{{ route('admin.regions.show', $region) }}">{{ $region->name }}</a></td>
                <td>{{ $region->slug }}</td>
                <td>{{ $region->parent ? $region->parent->name : '' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $regions->links() }}
@endsection
