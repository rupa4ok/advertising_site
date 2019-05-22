@extends('layouts.app')

@section('content')
    @include('cabinet.profile._nav')
    
    <div class="mb-3">
        <a href="{{ route('cabinet.profile.edit') }}" class="btn btn-primary">Edit</a>
    </div>
    
    <table class="table table-bordered">
        <tbody>
        <tr>
            <th>First Name</th><td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Last Name</th><td>{{ $user->last_name }}</td>
        </tr>
        <tr>
            <th>Email</th><td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>Phone</th><td>
                @if ($user->phone)
                    {{ $user->phone }}
                    @if (!$user->isPhoneVerified())
                        <i>(is not verified)</i><br />
                        <form method="POST" action="{{ route('cabinet.profile.phone') }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">Verify</button>
                        </form>
                    @endif
                    @if ($user->isPhoneVerified())
                    <span type="submit" class="btn btn-sm btn-success">Verified</span>
                    @endif
                @endif
            </td>
        </tr>
        </tbody>
    </table>
@endsection