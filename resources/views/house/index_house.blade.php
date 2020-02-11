
@extends('layouts.app')

@section('content')
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
    <a class="dropdown-item" href="{{ route('logout') }}"
       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
        {{ __('Logout') }}
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>
    <a href="{{action([\App\Http\Controllers\HousesController::class, 'create'])}}">Add</a>
    @foreach($houses as $house)
        <ul>
            <li>
                № {{$house->id}}. {{$house->description }}
                @auth
                @if(\Illuminate\Support\Facades\Auth::user()->houses->contains($house) || \Illuminate\Support\Facades\Auth::user()->isAdmin())
                    <a href="{{action('HousesController@edit', [ 'house' => $house ])}}">Update</a>
                @endif
                <a href="{{action('HousesController@show', [ 'house' => $house ])}}">View</a>
                <form method="POST" action="{{action('HousesController@destroy', [ 'house' => $house ])}}" >
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
                @endauth
            </li>
        </ul>
    @endforeach
@endsection
