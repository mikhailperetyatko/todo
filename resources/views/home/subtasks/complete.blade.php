@extends('layout.without_sidebar')
@section('content')
    @if(! $subtask->completed)
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
