@extends('layout.without_sidebar')
@section('content')
    <div class="container">
        <h5>Задача</h5>
        <form method="post" action="/home/subtasks/{{ $subtask->id }}" id="deleteForm">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
        </form>
        <div class="mb-2">
            @if(! $subtask->completed)
                <a class="btn btn-success btn-sm" role="button" href="/home/subtasks/{{ $subtask->id }}/completing">Выполнить</a>
            @elseif(! $subtask->finished)
                <a class="btn btn-danger btn-sm" role="button" href="/home/subtasks/{{ $subtask->id }}/uncompleted">Отменить выполнение</a>
            @endif
            
            @if($subtask->completed && ! $subtask->finished)
                <a class="btn btn-success btn-sm" role="button" href="/home/subtasks/{{ $subtask->id }}/finishing">Завершить</a>
            @elseif($subtask->finished)
                <a class="btn btn-danger btn-sm" role="button" href="/home/subtasks/{{ $subtask->id }}/unfinished">Отменить принятие</a>
            @endif
            @if($subtask->delayable)
                <button type="button" class="btn btn-info btn-sm d-inline">Отложить</button>
            @endif
            <a class="btn btn-info btn-sm" role="button" href="/home/subtasks/{{ $subtask->id }}/edit">Редактировать</a>
            <button type="submit" role="button" class="btn btn-danger btn-sm" onclick="if (confirm('Точно удалить?')) $('#deleteForm').submit()">Удалить</button>
        </div>
        <div class="card shadow">
            <div class="card-header">
                <a href="/home/projects/{{ $subtask->task->project->id }}">{{ $subtask->task->project->name }}</a> / <a href="/home/projects/{{ $subtask->task->project->id }}/tasks/{{ $subtask->task->id }}">{{ $subtask->task->name }}</a>
            </div>
            <div class="card-body">
                <p><b>Исполнитель</b> - {{ $subtask->executor->name }}</p>
                <p><b>Контроллер</b> - {{ $subtask->validator->name }}</p>
                <div class="dropdown-divider"></div>
                <p><b>Суть:</b> {{ $subtask->description }}</p>
                <p>
                @php
                    $diff = \Carbon\Carbon::now()->diffInDays($subtask->execution_date);
                @endphp
                    <b>Дата выполнения:</b> {{ $subtask->execution_date->format('d.m.Y в H:i') }} ({{ \Carbon\Carbon::now() > $subtask->execution_date ? ('просрочен на ' . $diff) : 'осталось - ' . $diff }} дн.)
                </p>
                <p>
                    <b>Сложность:</b> {{ $subtask->referenceDifficulty->name }}
                </p>
                <p>
                    <b>Приоритет:</b> {{ $subtask->referencePriority->name }}
                </p>
                <p>
                    <b>Очки за выполнение :</b> {{ $subtask->score }}
                </p>
                <div class="card mt-2">
                    <div class="card-body">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseComments" aria-expanded="false" aria-controls="collapseComments">Комментарии к задаче</button>
                        <comments :subtask_id='{{$subtask->id}}' :user='{{ auth()->user()->id }}' class="collapse" id="collapseComments">Подождите...</comments>
                    </div>
                </div>
                
                <div class="card mt-2">
                    <div class="card-body">
                        <b>Файлы к задаче:</b>
                        <file :subtask='{{ $subtask->id }}' :checkbox="false">Подождите...</file>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
