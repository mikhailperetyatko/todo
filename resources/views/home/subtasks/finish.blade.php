@extends('layout.without_sidebar')
@section('content')
    @if($subtask->completed && ! $subtask->finished)
        <div class="container">
            <h5>Принятие исполнения задачи</h5>
            @include('home.subtasks.history')
            <p>Суть задачи: {{ $subtask->description }}</p>
            <form method="post" action="/home/subtasks/{{ $subtask->id }}/finish">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="form-group">
                    <label for="formControlAnnotation">Пояснения</label>
                    <textarea class="form-control {{ $errors->get('validator_annotation') ? 'is-invalid' : '' }}" id="formControlAnnotation" name="validator_annotation" rows="3">{{ old('validator_annotation') ?? $subtask->validator_annotation ?? '' }}</textarea>
                    <div class="invalid-feedback">
                        {{ implode(',', $errors->get('validator_annotation') ?? []) }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlDecision">Решение</label>
                    <select class="form-control {{ $errors->get('decision') ? 'is-invalid' : '' }}" id="exampleFormControlDecision" name="decision" required>
                        <option disabled selected>Принять или не принять - вот в чем вопрос</option>
                        <option value="refuse">Отклонить</option>
                        <option value="accept">Принять</option>
                    </select>
                    <div class="invalid-feedback">
                        {{ implode(',', $errors->get('decision') ?? []) }}
                    </div>
                </div>
                @include('home.subtasks.repeat') 
                <div class="card mt-2">
                    <div class="card-body">
                        <b>Приложить файл</b>
                        <file :subtask='{{ $subtask->id }}' :checkbox="true">Подождите...</file>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block shadow">Завершить</button>
            </form>
        </div>
    @elseif(! $subtask->completed)
        <div class="alert alert-warning" role="alert">
            Пользователь {{ $subtask->executor->name }} еще не выполнил задачу
        </div>
    @else
        @php
            $acceptance = $subtask->acceptances()->latest()->first();
        @endphp
        <div class="alert alert-warning" role="alert">
            Пользователь {{ $acceptance->validator->name  }} уже выполнил эту задачу {{ $acceptance->annotation_date->format('d.n.Y в H:i:s')}}
        </div>
    @endif
@endsection
