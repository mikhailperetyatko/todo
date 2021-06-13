Было осуществлено следующее количество запросов:<br />
@foreach($state as $key => $item)
{{ $key + 1 }}) скрипт запущен в {{ $item['start'] }}, количество выявленных заявок - {{ $item['amount'] }} шт.<br \>
@endforeach