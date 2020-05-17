@extends('layout.without_sidebar')
@php
    $subtasksCounter = 0;
    $supremeCounter = 0;
    $highCounter = 0;
    $middleCounter = 0;
    $lowCounter = 0;
@endphp
@section('content')
    <div class="container">
        <h5>Сведения о задачах члена команды</h5>
        @foreach($projects as $subtasks)
            <div class="card shadow">
                <div class="card-header">
                    Проект "{{ $subtasks[0]->projectName }}"
                </div>
                <div class="card-body">
                    <ul>
                        @foreach($subtasks as $subtask)
                            <li>
                                {{ $subtask->description }}. Срок выполнения - {{ $subtask->execution_date->format('d.m.Y в H:i') }}
                                @if($subtask->validator->id == $user->id && $subtask->validator->id != $subtask->executor->id)
                                    <span class="badge badge-info">Контроллер</span>
                                @endif
                                @php
                                    switch($subtask->referenceDifficulty->value) {
                                        case 'supreme':
                                            $supremeCounter++;
                                            break;
                                        case 'high':
                                            $highCounter++;
                                            break;
                                        case 'middle':
                                            $middleCounter++;
                                            break;
                                        case 'low':
                                            $lowCounter++;
                                            break;
                                    }
                                    $subtasksCounter++;
                                @endphp
                            </li>
                        @endforeach
                    </ul>
                    <h6>Всего задач - {{ $subtasks->count() }} шт.</h6>
                </div>
            </div>
        @endforeach
        @if($subtasksCounter)
            <div class="alert alert-info" role="alert">
                <p class="mt-2">Всего задач - {{ $subtasksCounter }} шт., из них "особо сложных" - {{ $supremeCounter }} шт. ({{ round(($supremeCounter / $subtasksCounter * 100), 2) }}%), "сложных" - {{ $highCounter }} шт. ({{ round(($highCounter / $subtasksCounter * 100), 2) }}%), "средней сложности" - {{ $middleCounter }} шт. ({{ round(($middleCounter / $subtasksCounter * 100), 2) }}%), "простых" - {{ $lowCounter }} шт. ({{ round(($lowCounter / $subtasksCounter * 100), 2) }}%)</p>
            </div>
        @endif
    </div>
@endsection
