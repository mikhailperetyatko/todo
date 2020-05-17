@extends('layout.without_sidebar')
    
@section('content')
    <div class="container">
        <form method="post" action="/home/teams/{{ $team->id }}/assignOwner" id="myform">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="inputOwner">Владелец</label>
                <select id="inputOwner" class="form-control" name="owner">
                    @foreach($team->users as $user)
                        <option value="{{ $user->id }}" {{ $user->id == $team->owner->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <br />
            <input type="button" class="btn btn-primary" value="Сохранить" onclick="if ($('#newEmail').val()) {alert('Вероятно Вы забыли добавить нового пользователя');} else {$('#myform').submit();}">
        </form>
    </div>
@endsection
