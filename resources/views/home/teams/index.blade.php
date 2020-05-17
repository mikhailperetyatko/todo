@extends('layout.without_sidebar')
    
@section('content')
    <div class="container">
        <h5>Команды</h5>
        <a role="button" class="btn btn-primary mb-2" href="/home/teams/create">Добавить команду</a>
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
                                <th scope="col">Участники</th>
                                <th scope="col">Приглашенные</th>
                                <th scope="col">Смотреть</th>
                                <th scope="col">Редактировать</th>
                                <th scope="col">Удалить</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(! $teams->isEmpty())
                                @foreach($teams as $key => $team)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>{{ $team->name }}</td>
                                        @can('update', $team)
                                            <td>{{ implode(', ', $team->users->pluck('name')->toArray()) }}</td>
                                            <td>{{ implode(', ', $team->invitedToTeam->pluck('email')->toArray()) }}</td>
                                        @else
                                            <td>Информация скрыта</td>
                                            <td>Информация скрыта</td>
                                        @endcan
                                        <td>
                                            @can('view', $team)
                                                <a href="/home/teams/{{ $team->id }}" type="button" class="btn btn-primary btn-sm">Смотреть</button>
                                            @endcan
                                        </td><td>
                                            @can('update', $team)
                                                <a href="/home/teams/{{ $team->id }}/edit" type="button" class="btn btn-secondary btn-sm">Редактировать</button>
                                            @endcan
                                        </td><td>
                                            @can('update', $team)
                                                <button type="button" class="btn btn-danger btn-sm" onclick="if (confirm('Точно удалить?')) $('#myform').attr('action', '/home/teams/{{ $team->id }}').submit();">Удалить</button>
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
