@extends('layouts.app')

@section('content')
    @include('admin.users._nav')

    <form method="POST" action="{{ route('admin.users.store') }}">
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
            <label for="" class="col-form-label">Email</label>
            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                   name="email" value="{{ old('email') }}" required>
            @if ($errors->has('email'))
                <span class="invalid-feedback"><strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    
        <div class="form-group">
            <button class="btn btn-primary">Save</button>
        </div>
    </form>
@endsection
