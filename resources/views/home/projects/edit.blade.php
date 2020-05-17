@extends('layout.without_sidebar')
    
@section('content')
    <div class="container">
        <h5>Редактирование проекта</h5>
        <form method="post" action="/home/projects/{{ $project->id }}">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <div class="form-group">
                <label for="inputName">Название проекта</label>
                <input type="text" class="form-control {{ $errors->get('name') ? 'is-invalid' : '' }}" id="inputName" name="name" placeholder="Введите название" value="{{ old('name') ?? $project->name ?? '' }}" required>
                <div class="invalid-feedback">
                    {{ implode(',', $errors->get('name') ?? []) }}
                </div>
            </div>
            <div class="form-group">
            <label for="teamSelect">Команда</label>
            <select class="form-control" id="teamSelect" name="team">
                @foreach($teams as $team)
                    <option value="{{ $team->id }}" {{ $project->team->id == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                @endforeach
            </select>
          </div>
            <button type="submit" class="btn btn-primary">Изменить</button>
        </form>
    </div>
@endsection
