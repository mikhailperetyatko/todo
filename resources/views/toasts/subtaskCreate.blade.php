В рамках проекта "{{ $model->task->name }}" добавлено новое задание "{{ $model->description }}" со сроком исполнения до {{ $model->execution_date->format('H:i d.m.Y') }}, в котором Ваша роль определена как "{{ $role }}".