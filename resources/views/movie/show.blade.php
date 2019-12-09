@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card card-default">
                    <div class="card-header">{{ $title }}</div>
                    <ul>
                        @foreach($crews as $actor)
                            {{-- Check whether $actor has rel.roles or not, this is only available when role is 'ACTED_IN' --}}
                            @if($actor->hasValue('rel.roles'))
                                {{-- Implode will transform array to string, using connection symbol in the second parameter
                                    ; for example: ['a','b'] => "a,b" --}}
                                <li>{{ $actor->value('p.name') }} {{ $actor->value('type(rel)') }} {{ implode($actor->value('rel.roles'), ',') }}</li>
                            @else
                                <li>{{ $actor->value('p.name') }} {{ $actor->value('type(rel)') }}</li>
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