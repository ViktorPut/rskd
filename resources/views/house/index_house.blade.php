
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
                <a href="{{action('HousesController@edit', [ 'id' => $house->id ])}}">Update</a>
                <a href="{{action('HousesController@show', [ 'id' => $house->id ])}}">View</a>
                <a href="{{action('HousesController@destroy', [ 'id' => $house->id ])}}">Delete</a>
            </li>
        </ul>
    @endforeach
@endsection
