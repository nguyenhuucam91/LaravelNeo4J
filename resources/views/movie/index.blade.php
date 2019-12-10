@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <a href="{{ action('MovieController@create') }}">
                <button class="btn btn-primary">Create</button>
            </a>
            <div class="card card-default">
                <div class="card-header">Movies</div>
                <table id="results" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Movie</th>
                            <th>Released</th>
                            <th>Tagline</th>
                            <th>Actors</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                            <tr>
                                <td>{{ $record->get('m')->title }}</td>
                                <td>{{ $record->get('m')->released }}</td>
                                <td>{{ array_key_exists('tagline', $record->get('m')->values()) ? $record->get('m')->tagline : '' }}</td>
                                <td><a href="{{ action('MovieActorController@index', ['id' => $record->get('id(m)')]) }}">Actors</a></td>
                                <td>
                                    <a href="{{ action('MovieController@show', ['movie' => $record->get('id(m)') ]) }}">View</a>
                                    <a href="{{ action('MovieController@edit', ['movie' => $record->get('id(m)') ]) }}">Edit</a>
                                    <a href="javascript:void(0)" onclick="document.getElementById('delete-{{ $record->get('id(m)') }}').submit()">Delete</a>
                                    <form id="delete-{{ $record->get('id(m)') }}" action="{{ action('MovieController@destroy', ['movie' => $record->get('id(m)')]) }}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection