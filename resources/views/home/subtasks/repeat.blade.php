@if($subtask->task->subtasks()->where('finished', 0)->count() == 1)
    @php
        $nextDate = $subtask->task->interval_value 
            ? getDateFromInterval($subtask->task->referenceInterval->value, $subtask->task->interval_value, $subtask->task->execution_date)
            : \Carbon\Carbon::now()
        ;
    @endphp
    <div class="card">
        <div class="card-body">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="customSwitch1" name="repeat" {{ $subtask->task->repeatability || old('repeat')? 'checked' : '' }} >
                <label class="custom-control-label" for="customSwitch1">Назначить мероприятие "{{ $subtask->task->name }}" повторно</label>
            </div>
            <a class="btn btn-primary" data-toggle="collapse" href="#collapseRepeat" role="button" aria-expanded="false" aria-controls="collapseRepeat">
                Выбрать задачи, которые будут повторно назначены
            </a>
            <div class="collapse" id="collapseRepeat">
                <div class="card card-body">
                    @foreach($subtask->task->subtasks as $key => $value)
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSubtaskCheck{{ $key }}" name="subtasks_repeat[]" value="{{ $value->id }}" checked>
                            <label class="custom-control-label" for="customSubtaskCheck{{ $key }}">{{ $value->description }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label for="taskDate">Дата выполнения задачи</label>
                <input type="date" class="form-control {{ $errors->get('date') ? 'is-invalid' : '' }}" name="date" id="taskDate" placeholder="Дата" value="{{ old('date') ?? $nextDate->format('Y-m-d') }}">
                <div class="invalid-feedback">
                    {{ implode(',', $errors->get('date') ?? []) }}
                </div>
            </div>
            <div class="form-group">
                <label for="taskTime">Время выполнения задачи</label>
                <input type="time" class="form-control {{ $errors->get('time') ? 'is-invalid' : '' }}" id="taskTime" name="time" placeholder="Время" value="{{ old('time') ?? $nextDate->format('H:i') }}">
                <div class="invalid-feedback">
                    {{ implode(',', $errors->get('time') ?? []) }}
                </div>
            </div>
        </div>
    </div>
@endif
