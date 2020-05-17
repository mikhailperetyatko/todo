@extends('layout.without_sidebar')
@section('content')
<div class="container">
    <h5>Предустановленные задачи</h5>
    <a href="/home/preinstaller_tasks/create" role="button" class="btn btn-primary mb-2">Добавить</a>
    <form method="post" id="myform">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Название</th>
                            <th scope="col">Команда</th>
                            <th scope="col">Количество задач</th>
                            <th scope="col">Смотреть</th>
                            <th scope="col">Редактировать</th>
                            <th scope="col">Удалить</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(! $tasks->isEmpty())
                            @foreach($tasks as $key => $task)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $task->team ? $task->team->name : '-' }}</td>
                                    <td>{{ count($task->subtasks) }}</td>
                                    <td>
                                        @can('view', $task)
                                            <a href="/home/preinstaller_tasks/{{ $task->id }}" type="button" class="btn btn-primary">Смотреть</button>
                                        @endcan
                                    </td><td>
                                        @can('update', $task)
                                            <a href="/home/preinstaller_tasks/{{ $task->id }}/edit" type="button" class="btn btn-secondary">Редактировать</button>
                                        @endcan
                                    </td><td>
                                        @can('delete', $task)
                                            <button type="button" class="btn btn-danger" onclick="if (confirm('Точно удалить?')) $('#myform').attr('action', '/home/preinstaller_tasks/{{ $task->id }}').submit();">Удалить</button>
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

@endsection