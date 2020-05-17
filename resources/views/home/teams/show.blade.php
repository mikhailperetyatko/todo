@php
    $counter = 0;
    $projectCounter = 0;
@endphp
@extends('layout.without_sidebar')
    
@section('content')
    <div class="container">
        <h5>Команда "{{ $team->name }}"</h5>
        <form method="POST" id="deleteForm">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
        </form>
        @can('delete', $team)
            <a role="button" class="btn btn-primary mb-2" href="/home/teams/{{ $team->id }}/edit">Редактировать</a>
            <button type="button" class="btn btn-danger mb-2" onclick="if (confirm('Точно удалить?')) $('#deleteForm').submit();">Удалить</button>
        @endcan
        <h6>Участники:</h6>
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
                    @foreach($team->users as $user)
                        <tr>
                            <th scope="row">{{ ++$counter }}</th>
                            <td>
                                <a href="/home/teams/{{ $team->id }}/users/{{ $user->id }}">{{ $user->name }}</a><span class="badge badge-info ml-2">{{ $user->id == $team->owner->id ? 'Владелец' : '' }}</span>
                            </td>
                            <td>
                                {{ $user->email }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <h6>Проекты команды:</h6>
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
                    @foreach($team->projects as $project)
                        <tr>
                            <th scope="row">{{ ++$projectCounter }}</th>
                            <td>
                                {{ $project->name }}<span class="badge badge-info ml-2">{{ $user->id == $project->owner->id ? 'Владелец' : '' }}</span>
                            </td>
                            <td>
                                @can('view', $project)
                                    <a href="/home/projects/{{ $project->id }}" type="button" class="btn btn-primary btn-sm">Смотреть</a>
                                @endcan
                            </td>
                            <td>
                                <a href="/home/projects/{{ $project->id }}/tasks">{{ $project->tasks()->count() }} шт.</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection