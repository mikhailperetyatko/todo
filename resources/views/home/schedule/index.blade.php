@extends('layout.without_sidebar')
@php
    $shown = [];
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
                        <input type="checkbox" class="custom-control-input" id="customSwitch1" name="executor" {{ $onlyExecutor ? 'checked' : '' }}>
                        <label class="custom-control-label" for="customSwitch1">Только: я - исполнитель</label>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch2" name="validator" {{ $onlyValidator ? 'checked' : '' }}>
                        <label class="custom-control-label" for="customSwitch2">Только: я - контроллер</label>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch3" name="showable_not_need" {{ $showableNotNeed ? 'checked' : '' }}>
                        <label class="custom-control-label" for="customSwitch3">Не отображать напоминания</label>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch4" name="overdue_not_need" {{ $overdueNotNeed ? 'checked' : '' }}>
                        <label class="custom-control-label" for="customSwitch4">Не отображать просроченные задачи</label>
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
    
    <h5 class="text-center w-100"><a role="button" class="btn btn-secondary mr-1" href="/home/schedule?from={{ $from->subWeek()->format('Y-m-d') }}&to={{ $to->subWeek()->format('Y-m-d') }}"><</a><button class="btn btn-secondary">{{ $from->addWeek()->format('d.m.Y') }} - {{ $to->addWeek()->format('d.m.Y') }}</button><a role="button" class="btn btn-secondary ml-1" href="/home/schedule?from={{ $from->addWeek()->format('Y-m-d') }}&to={{ $to->addWeek()->format('Y-m-d') }}">></a></h5>
    <div class="accordion w-100 shadow" id="accordion">
        @foreach($dateRange as $key => $date)
        @php
            $filteredSubtasks = collect($subtasks->filter(function ($value, $key) use ($date, $showableNotNeed, $overdueNotNeed, $shown) {
                $date = parseDate($date);
                return ($overdueNotNeed != 'on' && $value->execution_date < $date && ! in_array($value->id, $shown)) || ($value->execution_date->format('d.m.Y') == $date->format('d.m.Y')) || ($showableNotNeed != 'on' && ($value->showable_date->format('d.m.Y') == $date->format('d.m.Y')));
            })->all());
            $shown = array_merge($shown, collect($filteredSubtasks)->pluck('id')->toArray());
        @endphp
        <div class="card">
            <div class="card-header" id="heading{{ $key }}">
                <h5 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $key }}" aria-expanded="{{ $filteredSubtasks->count() > 0 }}" aria-controls="collapse{{ $key }}">
                        {{ $date }}. Задач - {{ $filteredSubtasks->count() }} шт.
                    </button>
                </h5>
            </div>
    
            <div id="collapse{{ $key }}" class="collapse{{ $filteredSubtasks->count() ? ' show' : ''}}" aria-labelledby="heading{{ $key }}" data-parent="#accordion">
                <div class="card-body">
                    @if($filteredSubtasks->isEmpty())
                        Задач не найдено
                    @else
                        @foreach($filteredSubtasks->sortBy('execution_date') as $subtaskKey => $subtask)
                            <div class="card mb-4 mt-4 {{ $subtask->referencePriority->value == 'high' ? 'border-danger shadow-lg' : ($subtask->referencePriority->value == 'middle' ? 'border-warning shadow' : ' shadow-sm') }}">
                                <div class="card-header text-center">
                                    Срок исполнения - <b>{{ $subtask->execution_date->format('d.m.Y в H:i') }}</b>
                                    @if($subtask->referencePriority->value == 'high')
                                        <span class="badge badge-danger">!ВАЖНАЯ!</span>
                                    @elseif($subtask->referencePriority->value == 'middle')
                                        <span class="badge badge-warning">!</span>
                                    @endif
                                    @if($subtask->showable_at < $subtask->execution_date && $subtask->execution_date > parseDate($date)->endOfDay())
                                        <span class="badge badge-primary">Напоминание</span>
                                    @endif
                                    @if($subtask->execution_date < $dateNow)
                                        <span class="badge badge-danger">ПРОСРОЧЕНО</span>
                                    @endif
                                    @if($subtask->completed && ! $subtask->finished)
                                        <span class="badge badge-success">ПРИНЯТЬ ИСПОЛНЕНИЕ</span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        Суть: {{ $subtask->description }}
                                        <br />
                                        <a href="/home/subtasks/{{ $subtask->id }}">Перейти к задаче</a>
                                    </p>
                                    <p class="card-text">
                                        Проект: {{ $subtask->task->project->name }}
                                    </p>
                                    @if($subtask->location)
                                    <p class="card-text">
                                        Место: {{ $subtask->location }}
                                    </p>
                                    @endif
                                    
                                    
                                    <a class="btn btn-secondary btn-sm btn-block" data-toggle="collapse" href="#collapseProperties{{ $key . '_' . $subtaskKey }}" role="button" aria-expanded="false" aria-controls="collapseProperties{{ $key . '_' . $subtaskKey }}">
                                        Отобразить дополнительную информацию
                                    </a>
                                    <div class="collapse" id="collapseProperties{{ $key . '_' . $subtaskKey }}">
                                        <div class="card card-body">
                                            <p class="card-text">
                                                Исполнитель - {{ $subtask->executor->name }}
                                            </p>
                                            <p class="card-text">
                                                Контроллер - {{ $subtask->validator->name }}
                                            </p>
                                            <p class="card-text">
                                                Сложность - {{ $subtask->referenceDifficulty->name }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    @if(! $subtask->completed)
                                        <a class="btn btn-success btn-sm" role="button" href="/home/subtasks/{{ $subtask->id }}/completing">Выполнить</a>
                                    @elseif(! $subtask->finished)
                                        <a class="btn btn-danger btn-sm" role="button" href="/home/subtasks/{{ $subtask->id }}/uncompleted">Отменить выполнение</a>
                                    @endif
                                    
                                    @if($subtask->completed && ! $subtask->finished)
                                        <a class="btn btn-success btn-sm" role="button" href="/home/subtasks/{{ $subtask->id }}/finishing">Завершить</a>
                                    @elseif($subtask->finished)
                                        <a class="btn btn-danger btn-sm" role="button" href="/home/subtasks/{{ $subtask->id }}/unfinished">Отменить принятие</a>
                                    @endif
                                    @if($subtask->delayable)
                                        <button type="button" class="btn btn-info btn-sm">Отложить</button>
                                    @endif
                                    <a class="btn btn-info btn-sm" role="button" href="/home/subtasks/{{ $subtask->id }}/edit">Редактировать</a>
                                </div>
                                <div class="card-footer text-muted">
                                    <a href="/home/projects/{{ $subtask->task->project->id }}">{{ $subtask->task->project->name }}</a> / 
                                    <a href="/home/projects/{{ $subtask->task->project->id }}/tasks/{{$subtask->task->id}}">{{ $subtask->task->name }}</a> / 
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>    
@endsection
