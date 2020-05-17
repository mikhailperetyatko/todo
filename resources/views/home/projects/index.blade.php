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
                        <input type="checkbox" class="custom-control-input" id="customSwitch1" name="onlyCompleted" {{ $onlyCompleted ? 'checked' : '' }}>
                        <label class="custom-control-label" for="customSwitch1">Только завершенные</label>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch2" name="onlyCurrent" {{ $onlyCurrent ? 'checked' : '' }}>
                        <label class="custom-control-label" for="customSwitch2">Только текущие</label>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch3" name="onlyMy" {{ $onlyMy ? 'checked' : '' }}>
                        <label class="custom-control-label" for="customSwitch3">Только: я - владелец</label>
                    </div>
                    <input type="submit" class="btn btn-outline-secondary btn-block" type="button" value="Отфильтровать">
                </div>
            </div>
        </form>
        <h5>Проекты</h5>
        <a role="button" class="btn btn-primary mb-2" href="/home/projects/create">Создать проект</a>
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
                                <th scope="col">Группа</th>
                                <th scope="col">Добавить задачу</th>
                                <th scope="col">Смотреть</th>
                                <th scope="col">Редактировать</th>
                                <th scope="col">Завершить/Возобновить</th>
                                <th scope="col">Удалить</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(! $projects->isEmpty())
                                @foreach($projects as $project)
                                    <tr>
                                        <th scope="row">{{ ++$counter }}</th>
                                        <td>{{ $project->name }}<span class="badge badge-info ml-2">{{ $project->is_old ? 'Завершен' : '' }}</span></td>
                                        <td><a href="/home/teams/{{ $project->team->id }}">{{ $project->team->name }}</a></td>
                                        <td><a href="/home/projects/{{ $project->id }}/tasks/create" type="button" class="btn btn-primary btn-sm">+</a></td>
                                        <td>
                                            @can('view', $project)
                                                <a href="/home/projects/{{ $project->id }}" type="button" class="btn btn-primary btn-sm">Смотреть</button>
                                            @endcan
                                        </td><td>
                                            @can('update', $project)
                                                <a href="/home/projects/{{ $project->id }}/edit" type="button" class="btn btn-secondary btn-sm">Редактировать</button>
                                            @endcan
                                        </td><td>
                                            @can('update', $project)
                                                <a href="/home/projects/{{ $project->id }}/{{ $project->is_old ? 'resume' : 'complete' }}" type="button" class="btn btn-success btn-sm">{{ $project->is_old ? 'Возобновить' : 'Завершить' }}</button>
                                            @endcan
                                        </td><td>
                                            @can('delete', $project)
                                                <button type="button" class="btn btn-danger btn-sm" onclick="if (confirm('Точно удалить?')) $('#myform').attr('action', '/home/projects/{{ $project->id }}').submit();">Удалить</button>
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
