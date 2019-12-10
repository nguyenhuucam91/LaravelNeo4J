@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        
        <form action="{{ action('MovieController@update', ['movie' => $id]) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="{{ $movie->get('m')->value('title') }}"/>
            </div>

            <div class="form-group">
                <label>Released</label>
                <input type="text" name="released" class="form-control" value="{{ $movie->get('m')->value('released') }}"/>
            </div>

            <div class="form-group">
                <label>Tagline</label>
                <input type="text" name="tagline" class="form-control" value="{{ $movie->get('m')->value('tagline') }}"/>
            </div>
            <button type="submit">Update</button>
        </form>
    </div>
</div>
@endsection