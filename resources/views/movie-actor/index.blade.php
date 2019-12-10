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
                        @if($_cast['role'] !== null)
                            <td>{{ $_cast['name'] }} {{  }}</td>
                        @else
                            <td>{{ $_cast['name'] }}</td>
                        @endif

                        <td>
                            <select>
                                @foreach($roles as $key => $role)
                                    <option value="{{ $key }}" {{ $_cast }}>{{ $role }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>2</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
