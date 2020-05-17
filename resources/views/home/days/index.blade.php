@extends('layout.without_sidebar')
@section('content')
    <h5>Уточнение праздничных и рабочих дней</h5>
    <div class="container">
        <form method="post" action="/home/days" method="POST">
            {{ csrf_field() }}
            <days :year='{{ $year }}' :range='@json($dateRange)'>Подождите...</days>
            <div class="dropdown-divider"></div>
            <div class="card">
                <h6>Использовать мои настройки календаря для работы со следующими командами:</h6>
                @foreach($user->teams as $key => $team)
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" id="customSwitch{{ $key }}" {{ $user->teamsHasDays()->where('id', $team->id)->exists() ? 'checked' : '' }} name="teams[]" value="{{ $team->id }}">
                      <label class="custom-control-label" for="customSwitch{{ $key }}">{{ $team->name }}</label>
                    </div>
                @endforeach
            </div>
            <input class="btn btn-primary m-2" type="submit" value="Сохранить">
        </form>
    </div>
@endsection