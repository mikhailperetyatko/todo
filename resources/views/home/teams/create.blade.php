@extends('layout.without_sidebar')

@section('content')
    <div class="container">
        <h5>Создание новой команды</h5>
        <form method="post" action="/home/teams">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="inputName">Название комманды</label>
                <input type="text" class="form-control {{ $errors->get('name') ? 'is-invalid' : '' }}" id="inputName" name="name" placeholder="Введите название" value="{{ old('name') ?? '' }}" required>
                <div class="invalid-feedback">
                    {{ implode(',', $errors->get('name') ?? []) }}
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Создать</button>
        </form>
    </div>
@endsection
