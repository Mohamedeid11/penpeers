@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('admin.dashboard')}}@endsection
@section('content')
@if(Auth::guard('admin')->check())
    <p>user <b>{{Auth::guard('admin')->user()->name}}</b> is logged-in</p>
    <form method="POST" action="{{route('admin.post_logout')}}">
        @csrf
        <input type="submit" value="logout">
    </form>
@else
<p>Not logged-in</p>
    @endif
<p><b>language is : {{App::getLocale()}}</b></p>
<table>
    <thead>
    <tr>
        <th>user</th>
        <th>username</th>
    </tr>
    </thead>
    <tbody>
    @foreach(App\Models\User::all() as $user)
        <tr>
            <td>
                {{$user->name}}
            </td>
            <td>
                {{$user->username}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
