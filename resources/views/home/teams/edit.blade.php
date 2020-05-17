@extends('layout.without_sidebar')
    
@section('content')
    <div class="container">
        <h5>Редактирование команды</h5>
        <form method="post" action="/home/teams/{{ $team->id }}" id="myform">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <div class="form-group">
                <label for="inputName">Название комманды</label>
                <input type="text" class="form-control {{ $errors->get('name') ? 'is-invalid' : '' }}" id="inputName" name="name" placeholder="Введите название" value="{{ old('name') ?? $team->name }}" required>
                <div class="invalid-feedback">
                    {{ implode(',', $errors->get('name') ?? []) }}
                </div>
            </div>
            <invite :owner='@json($team->owner)' :roles='@json($roles)' :users='@json($team->users)' :invited='@json($team->invitedToTeam)' :errors='@json($errors->getMessages())' :news='@json(old("news"))'>Подождите...</invite>
            <br />
            <input type="button" class="btn btn-primary" value="Сохранить" onclick="if ($('#newEmail').val()) {alert('Вероятно Вы забыли добавить нового пользователя');} else {$('#myform').submit();}">
        </form>
    </div>
@endsection
