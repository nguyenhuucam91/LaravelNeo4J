@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card card-default">
                    <div class="card-header">{{ $title }}</div>
                    <ul>
                        @foreach($crews as $actor)
                            {{-- {{ dd($actor->value('rel.roles')) }} --}}
                            @if($actor->hasValue('rel.roles'))
                                <li>{{ $actor->value('p.name') }} {{ $actor->value('type(rel)') }} {{ implode($actor->value('rel.roles'), ',') }}</li>
                            @else
                                <li>{{ $actor->value('p.name') }} {{ $actor->value('type(rel)') }}</li>
                            @endif 
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection