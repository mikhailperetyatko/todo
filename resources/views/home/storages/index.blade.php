@extends('layout.without_sidebar')
    
@section('content')
    <form method="post" id="myform">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
    </form>
    <div class="container">
        <h5>
            Мои хранилища
        </h5>
        <a class="btn btn-primary mb-2" role="button" href="/home/storages/create">Добавить новое хранилище</a>
        <div class="table-responsive">
            @if($user->storages()->count())
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th>Наименование хранилища</th>
                            <th>Описание</th>
                            <th>Доступ до</th>
                            <th>Свободное место</th>
                            <th>Редактировать</th>
                            <th>Удалить</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->storages as $key => $storage)
                            <tr>
                                <th scope="row">{{ ++$key }}</th>
                                <td>
                                    <a href="/home/storages/{{ $storage->id }}">{{ $storage->name }}</a>
                                </td>
                                <td>{{ $storage->description }}</td>
                                <td>
                                    @if($storage->token_expires_at > \Carbon\Carbon::now()) 
                                        {{ $storage->token_expires_at->format('H:i:s d.m.Y') }}
                                    @else 
                                        Истек, <a href="/home/storages/{{ $storage->id }}/extend_token" role="button">продлить</a>
                                    @endif
                                </td>
                                @php
                                    $space = $storage->freeSpaceWithFormat();
                                @endphp
                                <td>{{ $space['free'] }} из {{ $space['total'] }}</td>
                                <td>
                                    <a class="btn btn-primary" href="/home/storages/{{ $storage->id }}/edit" role="button">Редактировать</a>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger" onclick="if (confirm('Точно удалить (будут удалены из системы также закгруженные файлы)?')) $('#myform').attr('action', '/home/storages/{{ $storage->id }}').submit();">Удалить</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div>Записей не найдено</div>
            @endif
        </div>
    </div>
@endsection