@extends('layouts.app')

@section('content')
    @include('admin.regions._nav')

    <p><a href="{{ route('admin.regions.create') }}" class="btn btn-success mr-1 mt-3">Add region</a></p>
    
    @include('admin.regions._list', ['regions' => $regions])

    {{ $regions->links() }}
@endsection
