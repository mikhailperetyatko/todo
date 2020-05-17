@extends('layout.without_sidebar')
@php
    $counter = 0;
@endphp
@section('content')
    <div class="container">
        <button class="btn btn-secondary btn-block mb-3" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
            Фильтры
        </button>
        <form>
            <div class="collapse mb-3 shadow" id="collapseFilter">
                <div class="card card-body">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch1" name="need_all" {{ $need_all ? 'checked' : '' }}>
                        <label class="custom-control-label" for="customSwitch1">Отобразить все мои задачи</label>
                    </div>
                    <input type="submit" class="btn btn-outline-secondary btn-block" type="button" value="Отфильтровать">
                </div>
            </div>
        </form>
        <a href="/home/projects/{{ $project->id }}/tasks/create" role="button" class="btn btn-primary mb-2">Добавить новое мероприятие в рамках проекта</a>
        <div class="table-responsive">
            <form method="post" id="myform">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Название</th>
                                <th scope="col">Проект</th>
                                <th scope="col">Смотреть</th>
                                <th scope="col">Редактировать</th>
                                <th scope="col">Удалить</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(! $tasks->isEmpty())
                                @foreach($tasks as $task)
                                    <tr>
                                        <th scope="row">{{ ++$counter }}</th>
                                        <td>{{ $task->name }}</td>
                                        <td><a href="/home/projects/{{ $task->project->id }}">{{ $task->project->name }}</a></td>
                                        <td>
                                            @can('view', $task)
                                                <a href="/home/projects/{{ $task->project->id }}/tasks/{{ $task->id }}" type="button" class="btn btn-primary">Смотреть</a>
                                            @endcan
                                        </td><td>
                                            @can('update', $task)
                                                <a href="/home/projects/{{ $task->project->id }}/tasks/{{ $task->id }}/edit" type="button" class="btn btn-secondary">Редактировать</a>
                                            @endcan
                                        </td><td>
                                            @can('delete', $task)
                                                <button type="button" class="btn btn-danger" onclick="if (confirm('Точно удалить?')) $('#myform').attr('action', '/home/projects/{{ $task->project->id }}/tasks/{{ $task->id }}').submit();">Удалить</button>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <th colspan="5">
                                        Записей не найдено
                                    </th>
                            @endif
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection
