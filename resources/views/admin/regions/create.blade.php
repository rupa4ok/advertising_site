@extends('layouts.app')

@section('content')
    @include('admin.regions._nav')
    <form method="POST" action="{{ route('admin.regions.store', ['parent' => $parent ? $parent->id : null]) }}">
        @csrf
    
        <div class="form-group">
            <label for="" class="col-form-label">Name</label>
            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
            name="name" value="{{ old('name') }}" required>
            @if ($errors->has('name'))
                <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
    
        <div class="form-group">
            <label for="" class="col-form-label">Slug</label>
            <input id="slug" type="slug" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}"
                   name="slug" value="{{ old('slug') }}" required>
            @if ($errors->has('slug'))
                <span class="invalid-feedback"><strong>{{ $errors->first('slug') }}</strong>
                </span>
            @endif
        </div>
    
        <div class="form-group">
            <button class="btn btn-primary">Save</button>
        </div>
    </form>
@endsection
