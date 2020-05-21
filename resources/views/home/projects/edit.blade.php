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
            <project-members :teams='@json($teams)' :project='@json($project)'>Подождите...</project-members>
            <button type="submit" class="btn btn-primary">Изменить</button>
        </form>
    </div>
@endsection
