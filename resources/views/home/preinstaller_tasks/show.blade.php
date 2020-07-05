@extends('layout.without_sidebar')
@section('content')
<div class="container">
    <form method="post" id="deleteForm">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
    </form>
    <h5>Предустановленная задача</h5>
    @can('delete', $preinstallerTask)
        <a href="/home/preinstaller_tasks/{{ $preinstallerTask->id }}/edit" role="button" class="btn btn-primary btn-sm mb-2">Редактировать</a>
        <button class="btn btn-danger btn-sm mb-2" onclick="if (confirm('Точно удалить?')) $('#deleteForm').submit();">Удалить</button>
    @else
        <p class="text-muted">Базовая предустановленная задача</p>
    @endcan
    <div class="card shadow">
        <div class="card-body">
            <p><b>Команда:</b> {{ $preinstallerTask->team ? $preinstallerTask->team->name : '-' }}</p>
            <p><b>Название:</b> {{ $preinstallerTask->name }}</p>
            <p><b>Повторяемость:</b> {{ $preinstallerTask->repeatability ? ('раз в ' . $preinstallerTask->interval_value . ' (' . $preinstallerTask->referenceInterval->name . ')' ) : 'Нет' }}</p>
            <p><b>Задачи:</b>
                @foreach($preinstallerTask->subtasks as $key => $subtask)
                    <div class="card">
                        <div class="card-header">
                            Задача #{{ $key + 1 }}
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">{{ $subtask['description'] }}</h6>
                            <p class="card-text">Напомнить за: {{ $subtask['showable_by'] }} дн.</p>
                            <p class="card-text">Сдвинуть задачу на: {{ $subtask['delay'] }} ({{ $interval->where('id', $subtask['reference_interval_id'])->first()->name }})</p>
                            <p class="card-text">Сложность: {{ $difficulty->where('id', $subtask['reference_difficulty_id'])->first()->name }}</p>
                            <p class="card-text">Приоритет: {{ $priority->where('id', $subtask['reference_priority_id'])->first()->name ?? ''}}</p>
                            <p class="card-text">Количество очков: {{ $subtask['score'] }}</p>
                            <p class="card-text">Возможно отсрочить: {{ $subtask['not_delayable'] ? 'Да' : 'Нет' }}</p>
                      </div>
                    </div>
                @endforeach
            </p>
        </div>
    </div>
</div>
@endsection