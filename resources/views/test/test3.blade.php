@php
require '/var/www/todo/resources/views/test/transaction3.php';

$createDate = function (string $string, int $yearPosition = 2) {
    $date = explode('.', $string);
    if (count($date) < 2) dd($date);
    if (strlen($date[$yearPosition]) === 2) $date[$yearPosition] = '20' . $date[$yearPosition];
    
    return \Carbon\Carbon::parse(implode('.', $date));
};

$transaction = [];
foreach ($data as $row) {
    //if ($row['number']) {
        //$number = $row['number'];
        //$transaction[$number] = [
        $transaction[] = [
            //'position' => $number,
            'position' => $row['number'],
            'id' => $row['id'] ?? '-',
            'operationDate' => $createDate($row['operationDate']),
            //'operationDate' => \Carbon\Carbon::parse($row['operationDate']),
            'documentCode' => $row['documentCode'],
            'documentNumber' => $row['documentNumber'],
            'documentDate' => $createDate($row['documentDate']),
            //'documentDate' => \Carbon\Carbon::parse(str_replace('/', '.', $row['documentDate'])),
            'contractorName' => $row['contractorName'],
            'inn' => $row['Inn'],
            'debt' => $row['debt'] !== '-' ? (float) str_replace('-', '.', str_replace([chr(194), chr(160)], '', $row['debt'])) : 0,
            'kredit' => $row['kredit'] !== '-' ? (float) str_replace('-', '.', str_replace([chr(194), chr(160)], '', $row['kredit'])) : 0,
            'description' => $row['description'],
        ];
    /*    
    } else {
        if ($row['documentCode']) $transaction[$number]['documentCode'] .= ' ' . $row['documentCode'];
        if ($row['documentNumber']) $transaction[$number]['documentNumber'] .= $row['documentNumber'];
        if ($row['contractorName']) $transaction[$number]['contractorName'] .= ' ' . $row['contractorName'];
        if ($row['description']) $transaction[$number]['description'] .= ' ' . $row['description'];
    }
    */
}

$transaction = collect($transaction)->filter(function($item) {
    return !$item['kredit'];
});
//echo number_format($transaction->reduce(function($accum, $item) {return $accum + $item['debt'];}, 0), 2, ' ', ',') . ' руб.';

$byContractor = $transaction->groupBy('inn')->map(function ($item, $inn) {
    $debtAmount = collect($item)->reduce(function($amount, $transaction) {
        return $amount + $transaction['debt'];
    }, 0);

    return [
        'name' => $item[0]['contractorName'],
        'inn' => $inn,
        'debtAmount' => $debtAmount,
        'transactions' => $item,
        'periodStart' => $item[0]['operationDate']->format('d.m.Y'),
        'periodEnd' => $item[count($item) - 1]['operationDate']->format('d.m.Y'),
        'amountInDay' => $debtAmount / (abs($item[count($item) - 1]['operationDate']->diffInDays($item[0]['operationDate'])) + 1),
        'averagePayment' => $debtAmount / count($item),
    ];
})->sortByDesc('debtAmount');

/*
$avaragePayment = $byContractor->reduce(function ($accum, $contractor) {
    return $accum + $contractor['averagePayment'];
}, 0) / count($byContractor);

$avaragePaymentInDay = $byContractor->reduce(function ($accum, $contractor) {
    return $accum + $contractor['amountInDay'];
}, 0) / count($byContractor);

$byContractor = $byContractor->filter(function ($contractor) use ($avaragePaymentInDay) {
    return $contractor['amountInDay'] > $avaragePaymentInDay;
    //return $contractor['averagePayment'] > $avaragePayment;
});

@if($contractor['inn'] != 7744000912 && $contractor['inn'] != 7842052027 && $contractor['debtAmount'] > 0)
*/
@endphp

@foreach($byContractor as $key => $contractor)
  @if($contractor['debtAmount'] > 0)
    <p></p>
    <table border="1" style="border-collapse:collapse; font-size:10pt">
      <tr>
        <td colspan="8" style="text-align: center">{{ $contractor['name'] }} (ИНН: {{ $contractor['inn'] }}), оборот за период {{ $contractor['periodStart'] }} - {{ $contractor['periodEnd'] }} составил {{ number_format($contractor['debtAmount'], 2, ',', ' ') }} руб.</td>
      </tr>
      <tr>
        <th>№ позиции</th>
        <th>№ счета</th>
        <th>Дата операции</th>
        <th>Код документа</th>
        <th>№ документа</th>
        <th>Дата документа</th>
        <th>Сумма</th>
        <th>Назначение</th>
      </tr>
      @foreach ($contractor['transactions'] as $transaction)
        <tr>
          <td>{{ $transaction['position'] }}</td>
          <td>{{ $transaction['id'] }}</td>
          <td>{{ $transaction['operationDate']->format('d.m.Y') }}</td>
          <td>{{ $transaction['documentCode'] }}</td>
          <td>{{ $transaction['documentNumber'] }}</td>
          <td>{{ $transaction['documentDate']->format('d.m.Y') }}</td>
          <td>{{ number_format($transaction['debt'], 2, ',', ' ') }}</td>
          <td>{{ $transaction['description'] }}</td>
        </tr>
      @endforeach
      <tr>
        <th colspan="5">ИТОГО</th>
        <th>{{ number_format($contractor['debtAmount'], 2, ',', ' ') }}</th>
        <th>Х</th>
      </tr>
    </table>
    @if(count($contractor['transactions']) > 1000)
      <p style="page-break-after: always"></p>
    @endif
  @endif
@endforeach