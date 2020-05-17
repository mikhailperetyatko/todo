<a class="btn btn-primary" data-toggle="collapse" href="#collapseAcceptances" role="button" aria-expanded="false" aria-controls="collapseAcceptances">
    История исполнения задачи
</a>
<div class="collapse" id="collapseAcceptances">
    @foreach($subtask->acceptances as $acceptance)
        <div class="card m-1">
            <div class="card-header">
            {{ $acceptance->executor->name }} -> {{ $acceptance->validator->name }}
            </div>
            <div class="card-body">
                <h6 class="card-title">Отчет исполнителя ({{ $acceptance->executor->name }}, {{ $acceptance->report_date->format('d.m.Y в H:i:s') }})</h6>
                <p class="card-text">{{ $acceptance->executor_report }}</p>
                @if($acceptance->validator_annotation)
                    <div class="dropdown-divider"></div>
                    <h6 class="card-title">Решение контроллера ({{ $acceptance->validator->name }}, {{ $acceptance->annotation_date->format('d.m.Y в H:i:s') }})</h6>
                    <p class="card-text">{{ $acceptance->validator_annotation }}</p>
                @endif
                @if($acceptance->files->count())
                    <div class="dropdown-divider"></div>
                    <p class="card-text">Файлы:
                        @foreach($acceptance->files as $key => $file)
                            <a href="/home/subtasks/{{ $subtask->id }}/files/{{ $file->id }}" target="_blank">{{ $file->name }}</a>
                            @if($key + 1 < $acceptance->files->count())
                            {{','}}
                            @endif
                        @endforeach
                    </p>
                @endif
            </div>
        </div>
    @endforeach
    @if(! $subtask->acceptances->count())
        Записей нет
    @endif
</div>