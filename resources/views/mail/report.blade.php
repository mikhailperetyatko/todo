@component('mail::message')
<h1>
Отчет
</h1>
@foreach($tables as $value)
<p>{{ $value['name'] }} - {{ $value['data'] }}</p>
@endforeach
@component('mail::button', ['url' => url('/admin/reports/' . $attach), 'color' => 'blue'])
Скачать файл отчета
@endcomponent
<p>Файл отчета доступен на скачивание до {{ $timeBeforeDelete }}
@endcomponent
