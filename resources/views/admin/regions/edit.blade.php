@extends('layouts.app')

@section('content')
    @include('admin.regions._nav')
    
    <form method="POST" action="{{ route('admin.regions.update', $region) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="" class="col-form-label">Name</label>
            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                   name="name" value="{{ old('name', $region->name) }}" required>
            @if ($errors->has('name'))
                <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
        
        <div class="form-group">
            <label for="slug" class="col-form-label">SLUG</label>
            <input id="slug" type="slug" class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}"
                   name="slug" value="{{ old('slug', $region->slug) }}" required>
            @if ($errors->has('slug'))
                <span class="invalid-feedback"><strong>{{ $errors->first('slug') }}</strong></span>
            @endif
        </div>
        
        <div class="form-group">
            <button class="btn btn-primary">Save</button>
        </div>
    </form>
@endsection
