@component('mail::message')
    <h1>
        Отчет
    </h1>
    @foreach($tables as $tableName => $value)
    @lang("messages.tables.$tableName.name") - {{ $value }}
    @endforeach

    
@endcomponent
