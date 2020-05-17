@php
    $counter = 0;
    $tasksCounter = 0
    
@endphp
@extends('layout.without_sidebar')
    
@section('content')
    
    <div class="container">
        <h5>Проект "{{ $project->name }}"</h5>
        <form method="POST" id="deleteForm">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
        </form>
        @can('delete', $project)
            <a role="button" class="btn btn-primary mb-2" href="/home/projects/{{ $project->id }}/edit">Редактировать</a>
            <button type="button" class="btn btn-danger mb-2" onclick="if (confirm('Точно удалить?')) $('#deleteForm').submit();">Удалить</button>
        @endcan
        <h6>
            Доступные участники:
        </h6>
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
            Мероприятия в проекте:
        </h6>
        <a href="/home/projects/{{ $project->id }}/tasks/create" role="button" class="btn btn-sm btn-primary mb-2">Добавить</a>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Название</th>
                        <th scope="col">Смотреть</th>
                        <th scope="col">Задачи</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($project->tasks as $task)
                        <tr>
                            <th scope="row">{{ ++$tasksCounter }}</th>
                            <td>
                                {{ $task->name }}<span class="badge badge-info ml-2">{{ auth()->user()->id == $task->owner->id ? 'Владелец' : '' }}</span>
                            </td>
                            <td>
                                @can('view', $task)
                                    <a href="/home/projects/{{ $project->id }}/tasks/{{ $task->id }}" type="button" class="btn btn-primary btn-sm">Смотреть</a>
                                @endcan
                            </td>
                            <td>
                                @can('view', $task)
                                    <a href="/home/projects/{{ $project->id }}/tasks/{{ $task->id }}">{{ $task->subtasks()->count() }} шт.</a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection