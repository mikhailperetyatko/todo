@extends('layout.without_sidebar')
@section('content')
    <div class="container">
        <h5>Редактирование предустановленной задачи</h5>
        <form method="post" action="/home/preinstaller_tasks/{{ $preinstallerTask->id }}">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <div class="form-group">
                <label for="inputName">Название задачи</label>
                <input type="text" class="form-control {{ $errors->get('name') ? 'is-invalid' : '' }}" id="inputName" name="name" placeholder="Введите название" value="{{ old('name') ?? $preinstallerTask->name }}" required>
                <div class="invalid-feedback">
                    {{ implode(',', $errors->get('name') ?? []) }}
                </div>
            </div>
            <div class="form-group">
                <label for="inputTeam">Выбор команды</label>
                <select class="form-control {{ $errors->get('team') ? 'is-invalid' : '' }}" id="inputTeam" name="team" required>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ $preinstallerTask->team->id == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{ implode(',', $errors->get('team') ?? []) }}
                </div>
            </div>
            <div>
                <task-repeatability :intervals='@json($selects['intervals'])' :old='@json(old())' :load='@json($preinstallerTask)' :errors='@json($errors->getMessages())'></task-repeatability>
                <div class="card">
                    <div class="card-body">
                        <h6>Мероприятия:</h6>
                        <div class="form-group">
                            <input type="hidden" class="form-control {{ $errors->get('subtasks') ? 'is-invalid' : '' }}" id="subtasks">
                            <div class="invalid-feedback">
                                {{ implode(',', $errors->get('subtasks') ?? []) }}
                            </div>
                        </div>
                        <attach-subtask :selects='@json($selects)' :old='@json(old())' :load='@json($preinstallerTask)' :errors='@json($errors->getMessages())'></attach-subtask>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block shadow">Изменить</button>
        </form>
    </div>
@endsection