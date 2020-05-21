<h3>
    Внимание! Уважаемый участник группы "{{ $subtask->task->project->team->name }}"
</h3>
Повторно обращаю Ваше внимание, что задача <u>"{{ $subtask->description }}"</u> (мероприятие "{{ $subtask->task->name }}") должна быть завершена в срок до {{ $subtask->execution_date->format('H:i d.m.Y') }}. Вместе с тем, она так и не была завершена до настоящего времени. Указанная задача имеет приоритет "{{ $subtask->referencePriority->name }}", в связи с чем, важным является проверить исполнителя ({{ $subtask->executor->name }}), поскольку, возможно, требуется помощь.
<a href="{{ config('app.url') }}/home/subtasks/{{ $subtask->id }}">Перейти к задаче</a>