<h3>
    Внимание! Уважаемый участник группы "{{ $subtask->task->project->team->name }}"
</h3>
Задача <u>"{{ $subtask->description }}"</u> (мероприятие "{{ $subtask->task->name }}") должна быть завершена в срок до {{ $subtask->execution_date->format('H:i d.m.Y') }}. Вместе с тем, она так и не была завершена даже спустя разумное время ({{ config ('app.delay_before_notify_about_subtask_event') }} ч.). Указанная задача имеет приоритет "{{ $subtask->referencePriority->name }}", в связи с чем, важным является проверить исполнителя ({{ $subtask->executor->name }}), поскольку, возможно, требуется помощь.
<a href="{{ config('app.url') }}/home/subtasks/{{ $subtask->id }}">Перейти к задаче</a>