@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">Movies</div>
                <table id="results" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Movie</th>
                            <th>Released</th>
                            <th>Tagline</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                            <tr>
                                <td>{{ $record->get('m')->title }}</td>
                                <td>{{ $record->get('m')->released }}</td>
                                <td>{{ $record->get('m')->tagline }}</td>
                                <td>
                                    <a href="{{ action('MovieController@view', ['title' => $record->get('m')->title ]) }}">View</a>
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