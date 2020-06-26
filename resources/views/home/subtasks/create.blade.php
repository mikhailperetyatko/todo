@extends('layout.without_sidebar')

@section('content')
    <div class="container">
        <h5>Добавление новой задачи в рамках мероприятия "{{ $subtask->task->name }}"</h5>
        <form method="post" action="/home/projects/{{ $subtask->task->project->id }}/tasks/{{ $subtask->task->id }}/subtasks">
            {{ csrf_field() }}
            {{ method_field('POST') }}
            <div class="form-group">
                <label for="formControlDescription">Описание задачи</label>
                <textarea class="form-control {{ $errors->get('description') ? 'is-invalid' : '' }}" id="formControlDescription" name="description" rows="3" required>{{ old('description') ?? '' }}</textarea>
                <div class="invalid-feedback">
                    {{ implode(',', $errors->get('description') ?? []) }}
                </div>
            </div>
            
            <div class="form-group">
                <label for="taskDate">Дата выполнения задачи</label>
                <input type="date" class="form-control {{ $errors->get('date') ? 'is-invalid' : '' }}" name="date" id="taskDate" placeholder="Дата" value="{{ old('date') ?? '' }}" required>
                <div class="invalid-feedback">
                    {{ implode(',', $errors->get('date') ?? []) }}
                </div>
            </div>
            <div class="form-group">
                <label for="taskTime">Время выполнения задачи</label>
                <input type="time" class="form-control {{ $errors->get('time') ? 'is-invalid' : '' }}" id="taskTime" name="time" placeholder="Время" value="{{ old('time') ?? '' }}" required>
                <div class="invalid-feedback">
                    {{ implode(',', $errors->get('time') ?? []) }}
                </div>
            </div>
            <div class="form-group">
                <label for="formControlSelectExecutor">Выбор исполнителя</label>
                <select class="form-control {{ $errors->get('user_executor') ? 'is-invalid' : '' }}" id="formControlSelectExecutor" name="user_executor" {{ policy($subtask)->delegation(auth()->user(), $subtask) ? '' : 'disabled'}}>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{ implode(',', $errors->get('user_executor') ?? []) }}
                </div>
            </div>
            <div class="form-group">
                <label for="formControlSelectValidator">Выбор контроллера</label>
                <select class="form-control {{ $errors->get('user_validator') ? 'is-invalid' : '' }}" id="formControlSelectValidator" name="user_validator">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{ implode(',', $errors->get('user_validator') ?? []) }}
                </div>
            </div>
            <a class="btn btn-primary mb-3" data-toggle="collapse" href="#collapseExt" role="button" aria-expanded="false" aria-controls="collapseExt">
                Дополнительные свойства
            </a>
            <div class="collapse" id="collapseExt">
                <div class="card card-body">
                    
                    <div class="card card-body">
                        <div class="form-group">
                            <label for="inputDelay">Сдвинуть срок исполнения на </label>
                            <input type="text" class="form-control {{ $errors->get('delay') ? 'is-invalid' : '' }}" id="inputDelay" aria-describedby="Количество" name="delay" value="{{ old('delay') ?? $subtask->delay ?? ''}}">
                            <div class="invalid-feedback">
                                {{ implode(',', $errors->get('delay') ?? []) }}
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="formControlSelectInterval">Выбор типа интервала</label>
                            <select class="form-control {{ $errors->get('delay_interval') ? 'is-invalid' : '' }}" id="formControlSelectInterval" name="delay_interval">
                                @foreach($intervals as $interval)
                                    <option value="{{ $interval->value }}">{{ $interval->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                {{ implode(',', $errors->get('delay_interval') ?? []) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputShowableBy">Включить в повестку за ... (дни) до срока выполнения задачи </label>
                        <input type="text" class="form-control {{ $errors->get('showable_by') ? 'is-invalid' : '' }}" id="inputShowableBy" aria-describedby="Количество дней" name="showable_by" value="{{ old('showable_by') ?? '' }}">
                        <div class="invalid-feedback">
                            {{ implode(',', $errors->get('showable_by') ?? []) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="formControlSelectDifficulty">Сложность</label>
                        <select class="form-control {{ $errors->get('difficulty') ? 'is-invalid' : '' }}" id="formControlSelectDifficulty" name="difficulty">
                            @foreach($difficulties as $difficulty)
                                <option value="{{ $difficulty->value }}">{{ $difficulty->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            {{ implode(',', $errors->get('difficulty') ?? []) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="formControlSelectPriority">Приоритет</label>
                        <select class="form-control {{ $errors->get('priority') ? 'is-invalid' : '' }}" id="formControlSelectPriority" name="priority">
                            @foreach($priorities as $priority)
                                <option value="{{ $priority->value }}">{{ $priority->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            {{ implode(',', $errors->get('priority') ?? []) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputLocation">Место выполнения задачи</label>
                        <input type="text" class="form-control {{ $errors->get('location') ? 'is-invalid' : '' }}" id="inputLocation" aria-describedby="Количество дней" name="location" value="{{ old('location') ?? '' }}">
                        <div class="invalid-feedback">
                            {{ implode(',', $errors->get('location') ?? []) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputScore">Количество очков за выполнение задачи</label>
                        <input type="text" class="form-control {{ $errors->get('score') ? 'is-invalid' : '' }}" id="inputScore" aria-describedby="Количество очков за выполнение задачи" name="score" value="{{ old('score') ?? '' }}">
                        <div class="invalid-feedback">
                            {{ implode(',', $errors->get('score') ?? []) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input {{ $errors->get('not_delayable') ? 'is-invalid' : '' }}" id="customSwitchDelayable" name="not_delayable">
                            <label class="custom-control-label" for="customSwitchDelayable">Отложить задачу невозможно</label>
                        </div>
                        <div class="invalid-feedback">
                            {{ implode(',', $errors->get('not_delayable') ?? []) }}
                        </div>
                    </div>
                </div>
            </div>
            
            
            
            <button type="submit" class="btn btn-primary btn-block shadow">Создать</button>
        </form>
    </div>
@endsection
