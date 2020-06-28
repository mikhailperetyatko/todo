@php
    $monthNames = [
        '', 'Январь', 'Февраль', 'Март', 'Арель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
    ];
    
@endphp
@extends('layout.without_sidebar')

@section('content')
    <div class="container">
        <button class="btn btn-secondary btn-block mb-3" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
            Фильтры
        </button>
        <form>
            <div class="collapse mb-3 shadow" id="collapseFilter">
                <div class="card card-body">
                    
                    <input type="submit" class="btn btn-outline-secondary btn-block" type="button" value="Отфильтровать">
                </div>
            </div>
        </form>
    </div>
    <div class="container">
    <div class="text-center w-100"><a role="button" class="btn btn-secondary mr-1" href="/home/schedule/calendar?offset=-1&year={{ (int) $year }}&month={{ (int) $month }}"><</a><button class="btn btn-secondary">{{ $monthNames[(int)$month] }} {{ $year }} года</button><a role="button" class="btn btn-secondary ml-1" href="/home/schedule/calendar?offset=1&year={{ (int) $year }}&month={{ (int) $month }}">></a></div>
        @foreach ($dateRange as $monthNumber => $month)
        <div class="shadow p-1 col-lg-7">
            <div class="card shadow">
                <h5 class="card-header">{{ $monthNames[$monthNumber] }} {{ $year }} года</h5>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ПН</th>
                                    <th scope="col">ВТ</th>
                                    <th scope="col">СР</th>
                                    <th scope="col">ЧТ</th>
                                    <th scope="col">ПТ</th>
                                    <th scope="col">СБ</th>
                                    <th scope="col">ВС</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($month as $weekNumber => $week)
                                <tr>
                                    <th scope="row">{{ $weekNumber }}</th>
                                    @foreach ([1, 2, 3, 4, 5, 6, 7] as $day)
                                    <td>
                                        @if(isset($week[$day]))
                                        <div>
                                            <a href="/home/schedule?from={{ $week[$day]['date'] }}&to={{ $week[$day]['date'] }}" class="{{ isset($week[$day]) && $week[$day]['is_holiday'] ? 'text-danger' : '' }}">{{ $week[$day]['day'] }}</a>
                                            @php
                                                $subtask = $subtasks->where('group_date', $week[$day]['date'])->first();
                                            @endphp
                                            @if ($subtask)
                                            <span class="badge badge-primary">{{ $subtask->amount }}</span>
                                            @endif
                                        </div>
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>    
@endsection
