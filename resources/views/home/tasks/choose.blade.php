@extends('layout.without_sidebar')
    
@section('content')
    <div class="container">
        <div class="form-group">
            <label for="teamSelect">Выберите проект</label>
            <select class="form-control" id="teamSelect" name="team" onChange="window.location='/home/projects/' + this.value + '/tasks/create'">
                <option disabled selected>Выберите проект...</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary" onClick="window.location='/home/projects/' + $('#teamSelect').val() + '/tasks/create'">Далее</button>
    </div>
@endsection
