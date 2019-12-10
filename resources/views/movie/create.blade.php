@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <form action="{{ action('MovieController@store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control"/>
            </div>

            <div class="form-group">
                <label>Released</label>
                <input type="text" name="released" class="form-control"/>
            </div>

            <div class="form-group">
                <label>Tagline</label>
                <input type="text" name="tagline" class="form-control"/>
            </div>
            <button type="submit">Create</button>
        </form>
    </div>
</div>
@endsection