@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card card-default">
                    <div class="card-header">{{ $title }}</div>
                    <ul>
                        @foreach($cast as $_cast)
                            @if($_cast['role'] !== null)
                                <li>{{ $_cast['name'] }} {{ $_cast['job'] }} as {{ $_cast['role'] }}</li>
                            @else
                                <li>{{ $_cast['name'] }} {{ $_cast['job'] }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                <a href="{{ action('MovieController@index') }}">
                    <button>Back to home</button>
                </a>
            </div>
        </div>
    </div>
@endsection