@extends('layout.without_sidebar')
@section('content')
    <div class="container">
        <button class="btn btn-secondary btn-block mb-3" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
            Фильтры
        </button>
        <form action="/home/history">
            <div class="collapse mb-3 shadow" id="collapseFilter">
                <div class="card card-body">
                    <div class="card">
                        <h6>Отобразить только этот проект:</h6>
                        @foreach($user->projects as $key => $userProject)
                            <div class="custom-control custom-switch">
                                <input type="radio" class="form-check-input" id="radioProject{{ $key }}" value="{{ $userProject->id }}" name="project">
                                <label class="form-check-label" for="customSwitchProject{{ $key }}">{{ $userProject->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <label for="inputDateFrom">Начало периода</label>
                        <input type="date" aria-label="Начало периода" placeholder="С даты" class="form-control" name="from" value="{{ $from->format('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="inputDateFrom">Конец периода</label>
                        <input type="date" aria-label="Конец период" placeholder="По дату" class="form-control" name="to" value="{{ $to->format('Y-m-d') }}">
                    </div>
                    <input type="submit" class="btn btn-outline-secondary btn-block" type="button" value="Отфильтровать">
                </div>
            </div>
        </form>
    </div>
    <h5 class="text-center w-100">Выполненные задачи за период <u>{{ $from->format('d.m.Y') }} - {{ $to->format('d.m.Y') }}</u></h5>
    @php
        $counter = 0;
    @endphp
    <div class="accordion" id="accordionMarker" class="w-100">
    @foreach($markers as $name => $projects)
        <div class="card">
            <div class="card-header" id="heading{{ ++$counter }}" data-toggle="collapse" data-target="#collapse{{ $counter }}" aria-expanded="false" aria-controls="collapse{{ $counter }}">
                {{ $name }}
            </div>
            <div id="collapse{{ $counter }}" class="collapse" aria-labelledby="heading{{ $counter }}" data-parent="#accordionMarker">
                <div class="card-body">
                    @foreach($projects as $project_id => $submarkers)
                            <h4 class="card-title">Проект "{{ $projectsName[$project_id] }}"</h4>
                            @foreach($submarkers as $submarker => $collection)
                                <h5><u>{{ $counter == count($markers) ? $collection[0]->task_name : $submarker }}</u></h5>
                                <ul class="list-group list-group-flush">
                                    @foreach($collection as $name => $element)
                                        <li class="list-group-item">
                                            @if($element instanceof \Illuminate\Support\Collection)
                                                <h6>{{ $element[0]->task_name }} от {{ $element[0]->task_execution_date->format('d.m.Y') }}</h6>
                                                @foreach($element as $submarker => $subtask)
                                                    <p class="card-text">{{ $subtask->description }} ({{ $subtask->execution_date->format('d.m.Y в H:i') }})</p>
                                                @endforeach
                                            @else
                                                <p class="card-text">{{ $element->description }}</p>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endforeach
                    @endforeach
                    @if($projects->isEmpty())
                        <p class="card-text">Записей не найдено...</p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
    </div>
@endsection