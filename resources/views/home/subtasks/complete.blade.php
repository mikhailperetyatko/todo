@extends('layout.without_sidebar')
@section('content')
    @if($subtask)
        <div class="container">
            <h5>Отчет об выполнении задачи</h5>
            @include('home.subtasks.history')
            <p>Суть задачи: {{ $subtask->description }}</p>
            <form method="post" action="/home/subtasks/{{ $subtask->id }}/complete">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="form-group">
                    <label for="formControlReport">Отчет (дополнительные пояснения)</label>
                    <textarea class="form-control {{ $errors->get('executor_report') ? 'is-invalid' : '' }}" id="formControlReport" name="executor_report" rows="3">{{ old('executor_report') ?? $subtask->executor_report ?? '' }}</textarea>
                    <div class="invalid-feedback">
                        {{ implode(',', $errors->get('executor_report') ?? []) }}
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <p>Введите дату и время, если хотите отметить выполненной данную задачу и вновь ее назначить</p>
                        <div class="form-group">
                            <label for="subtaskDate">Дата выполнения задачи</label>
                            <input type="date" class="form-control {{ $errors->get('date') ? 'is-invalid' : '' }}" name="date_repeat" id="subtaskDate" placeholder="Дата" value="{{ old('date') ?? '' }}">
                            <div class="invalid-feedback">
                                {{ implode(',', $errors->get('date') ?? []) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subtaskTime">Время выполнения задачи</label>
                            <input type="time" class="form-control {{ $errors->get('time') ? 'is-invalid' : '' }}" id="subtaskTime" name="time_repeat" placeholder="Время" value="{{ old('time') ?? '' }}">
                            <div class="invalid-feedback">
                                {{ implode(',', $errors->get('time') ?? []) }}
                            </div>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitchStrictDate" name="strict_date">
                            <label class="custom-control-label" for="customSwitchStrictDate">Не переносить дату в связи с выходными</label>
                        </div>
                    </div>
                </div>
                @if(auth()->user()->id == $subtask->validator->id)
                    @include('home.subtasks.repeat')
                @endif
                <div class="card mt-2">
                    <div class="card-body">
                        <b>Приложить файл</b>
                        <file :subtask='{{ $subtask->id }}' :checkbox="true">Подождите...</file>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block shadow">Выполнить</button>
            </form>
        </div>
    @else
        @php
            $acceptance = $subtask->acceptances()->latest()->first();
        @endphp
        <div class="alert alert-warning" role="alert">
            <h5>Пользователь {{ $acceptance->executor->name }} уже выполнил эту задачу {{ $acceptance->report_date->format('d.n.Y в H:i:s') }} </h5>
        </div>
    @endif
@endsection
