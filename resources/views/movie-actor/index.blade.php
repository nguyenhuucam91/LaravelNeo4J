@extends ('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $title }}</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <td>Actor</td>
                    <td>Role</td>
                    <td>As</td>
                </tr>
            </thead>
            <tbody>
                @foreach($cast as $_cast)
                    <tr>
                        
                        <td>{{ $_cast['name'] }}</td>
                
                        <td>
                            {{ $_cast['job'] }}
                        </td>
                        <td>
                            <form class="form-inline" action="{{ action('MovieActorController@update', ['id' => $_cast['id']]) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <input type="hidden" name="film_id" value="{{ $id }}"/>
                                @if ($_cast['role'] !== null)
                                    <input name="as_role" value="{{ $_cast['role'] }}" class="form-control"/>
                                    <button>Update</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection