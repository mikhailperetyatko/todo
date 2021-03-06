@php
    $counter = 0;
    $subtaskCounter = 0;
@endphp
@extends('layout.without_sidebar')
    
@section('content')
    <div class="container">
        <h5  class="">Проект "{{ $project->name }}"</h5>
        <h6  class=""><u>Мероприятие</u> "{{ $task->name }}"</h5>
        <form method="POST" id="deleteForm">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
        </form>
        
        <a href="/home/projects/{{ $project->id }}/tasks/{{ $task->id }}/edit" role="button" class="btn btn-primary mb-3">Редактировать</a>

        @can('delete', $task)
            <button type="button" class="btn btn-danger mb-3" onclick="if (confirm('Точно удалить?')) $('#deleteForm').submit();">Удалить</button>
        @endcan
        <h6>Доступные участники:</h6>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Имя</th>
                        <th scope="col">email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($project->team->users as $user)
                        <tr>
                            <th scope="row">{{ ++$counter }}</th>
                            <td>
                                {{ $user->name }}<span class="badge badge-info ml-2">{{ $user->id == $project->owner->id ? 'Владелец' : '' }}</span>
                            </td>
                            <td>
                                {{ $user->email }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <h6>
            Задачи:
        </h6>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Суть</th>
                        <th scope="col">Срок исполнения</th>
                        <th scope="col">Исполнитель</th>
                        <th scope="col">Контроллер</th>
                        <th scope="col">Очки за задачу</th>
                        <th scope="col">Статус</th>
                        <th scope="col">Удалить</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($task->subtasks as $subtask)
                        @can('view', $subtask)
                        <tr>
                            <th scope="row">{{ ++$subtaskCounter }}</th>
                            <td>
                                <a href="/home/subtasks/{{ $subtask->id }}">{{ $subtask->description }}</a>
                                @if($subtask->referenceDifficulty->value == "high" || $subtask->referenceDifficulty->value == "supreme")
                                    <span class="badge badge-danger ml-2">{{ $subtask->referenceDifficulty->name }}</span>
                                @endif
                                @if($subtask->referencePriority->value == "high" || $subtask->referencePriority->value == "high")
                                    <span class="badge badge-warning ml-2">{{ $subtask->referencePriority->name }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $subtask->execution_date->format('d.m.Y в H:i') }}<span class="badge badge-danger ml-2">{{ (\Carbon\Carbon::now()->startOfDay() > $subtask->execution_date && ! $subtask->completed) ? 'Просрочена' : '' }}</span>
                            </td>
                            <td>{{ $subtask->executor->name }}</td>
                            <td>{{ $subtask->validator->name }}</td>
                            <td>{{ $subtask->score }}</td>
                            <td>
                                @if($subtask->finished)
                                    Завершена
                                @elseif($subtask->completed)
                                    Ожидает проверки
                                @else
                                    В работе
                                @endif
                            </td>
                            <td>
                            @can('delete', $task)
                                <button type="button" class="btn btn-danger btn-sm" onclick="if (confirm('Точно удалить?')) $('#deleteForm').attr('action', '/home/subtasks/{{ $subtask->id }}').submit();">Удалить</button>
                            @endcan
                            </td>
                        </tr>
                        @endcan
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection