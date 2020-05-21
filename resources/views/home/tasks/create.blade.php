@extends('layout.without_sidebar')

@section('content')
    <div class="container">
        <h5>Добавление мероприятия</h5>
        <form method="post" action="/home/projects/{{ $project->id }}/tasks">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="inputName">Название мероприятия</label>
                <input type="text" class="form-control {{ $errors->get('name') ? 'is-invalid' : '' }}" id="inputName" name="name" placeholder="Введите название" value="{{ old('name') ?? '' }}" required>
                <div class="invalid-feedback">
                    {{ implode(',', $errors->get('name') ?? []) }}
                </div>
            </div>
            <div class="form-group">
                <label for="taskDate">Дата выполнения мероприятия</label>
                <input type="date" class="form-control {{ $errors->get('date') ? 'is-invalid' : '' }}" name="date" id="taskDate" placeholder="Дата" value="{{ old('date') ?? '' }}" required>
                <div class="invalid-feedback">
                    {{ implode(',', $errors->get('date') ?? []) }}
                </div>
            </div>
            <div class="form-group">
                <label for="taskDate">Время выполнения мероприятия</label>
                <input type="time" class="form-control {{ $errors->get('time') ? 'is-invalid' : '' }}" id="taskTime" name="time" placeholder="Время" value="{{ old('time') ?? '' }}" required>
                <div class="invalid-feedback">
                    {{ implode(',', $errors->get('time') ?? []) }}
                </div>
            </div>
            <div>
                <task-repeatability :intervals='@json($selects['intervals'])' :old='@json(old())' :errors='@json($errors->getMessages())'></task-repeatability>
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Задачи в мероприятии:</h6>
                        <div class="form-group">
                            <input type="hidden" class="form-control {{ $errors->get('subtasks') ? 'is-invalid' : '' }}" id="subtasks">
                            <div class="invalid-feedback">
                                {{ implode(',', $errors->get('subtasks') ?? []) }}
                            </div>
                        </div>
                        <attach-subtask :preinstaller_tasks='@json($preinstallerTasks)' :selects='@json($selects)' :old='@json(old())' :errors='@json($errors->getMessages())' :users='@json($project->members)'></attach-subtask>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Маркер:</h6>
                        <my-marker :project_id="{{ $project->id }}"></my-marker>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block shadow">Создать</button>
        </form>
    </div>
@endsection
