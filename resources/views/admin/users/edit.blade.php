@extends('layouts.app')

@section('content')
    @include('admin.users._nav')
    
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="" class="col-form-label">Name</label>
            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                   name="name" value="{{ old('name', $user->name) }}" required>
            @if ($errors->has('name'))
                <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
        
        <div class="form-group">
            <label for="" class="col-form-label">Email</label>
            <input id="email" type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   name="email" value="{{ old('email', $user->email) }}" required>
            @if ($errors->has('email'))
                <span class="invalid-feedback"><strong>{{ $errors->first('email') }}</strong></span>
            @endif
        </div>
    
        <div class="form-group">
            <label for="status" class="col-form-label">Status</label>
            <select name="status" id="status" class="form-control {{ $errors->has('status') ? ' is-invalid' : '' }}">
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" {{ $value === old('status', $user->status) ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('status'))
                <span class="invalid-feedback"><strong>{{ $errors->first('status') }}</strong></span>
            @endif
        </div>
    
        <div class="form-group">
            <label for="status" class="col-form-label">Status</label>
            <select name="role" id="role" class="form-control {{ $errors->has('role') ? ' is-invalid' : '' }}">
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" {{ $value === old('status', $user->status) ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('status'))
                <span class="invalid-feedback"><strong>{{ $errors->first('status') }}</strong></span>
            @endif
        </div>
        
        <div class="form-group">
            <button class="btn btn-primary">Save</button>
        </div>
    </form>
@endsection
