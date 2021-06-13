@php

require('/var/www/todo/resources/views/test/suz.php');

function noise($text) {
  $map = [
    'А' => ['A', 'Å', 'Ä'],
    'а' => ['a', 'å', 'ä'],
    'О' => ['O', 'Ό'],
    'о' => ['o', 'ọ'],
    'Е' => ['E', 'Ė'],
    'е' => ['e', '℮'],
    'В' => ['B', 'ᗷ'],
    'Ж' => ['Җ'],
    'ж' => ['җ'],
    'К' => ['K'],
    'к' => ['k', 'ҝ'],
    'Т' => ['T'],
    'у' => ['y'],
    'Ф' => ['Փ'],
    'Н' => ['H'],
  ];
  
  $result = '';
  for ($i = 0; $i < mb_strlen($text); $i++) {
    $word = mb_substr($text, $i, 1);
    $result .= isset($map[$word]) ? (rand(0, 1) ? $map[$word][array_rand($map[$word])] : $word) : $word;
  }
  return $result;
}

$desc = collect($desc);

$mass = collect($mass)->map(function($item) use ($desc) {
  return array_merge($item, [
    'contract' => $desc->filter(function($el) use ($item) {
      return trim($el['name']) === trim($item['name']);
    })->map(function ($el) {
      return [
        'desc' => 'Трудовой договор №' . $el['number'] . ' от ' . $el['date'],
        'timestamp' => \Carbon\Carbon::parse($el['date'])->timestamp,
      ];
    })->sortByDesc('timestamp'),
  ]);
});

$emptyContract = $mass->filter(function($item) {
  return !$item['contract']->count();
});
if ($emptyContract->count()) dump($emptyContract);

$nomer = 0;

$mass = $mass->map(function($el) {
  $paids = collect($el['paids'])->map(function($item) use ($el){
    list($day, $month, $year) = explode('.', $item['date']);
    if (strlen($year) === 2) $year = '20' . $year;
    
    return [
      'amount' => (float) str_replace(',', '.', $item['amount']),
      'desc' => $item['desc'],
      'date' => \Carbon\Carbon::parse($day . '.' . $month . '.' . $year)->format('d.m.Y'),
    ];
  });
  $paidAmount = $paids->reduce(function ($accum, $item){
    return $accum += $item['amount'];
  });
  
  list($day, $month, $year) = explode('.', $el['debtDate']);
  if (strlen($year) === 2) $year = '20' . $year;
  
  $debtAmount = (float) str_replace(',', '.', $el['debtAmount']);
  $saldo = (float) ($debtAmount - $paidAmount);
  if (abs($saldo) < 0.02) $saldo = 0;
  
  return [
    'name' => $el['name'],
    'debtDate' => \Carbon\Carbon::parse($day . '.' . $month . '.' . $year),
    'debt' => $el['debt'],
    'debtAmount' => $debtAmount,
    'saldo' => $saldo,
    'paids' => $paids,
    'paidAmount' => (float) $paidAmount,
    'contract' => $el['contract'],
  ];
});

@endphp
<table style="border-collapse:collapse" border="1">
  @foreach($mass->sortBy('debtDate') as $debt)
  <tr>
    <td>{{ noise($debt['name']) }}</td>
    <td>{{ $debt['debtDate']->format('d.m.Y') }}</td>
    <td>Конкурсное производство</td>
    <td>{{ $debt['debt'] }}</td>
    <td>{{ $debt['contract']->first()['desc'] }}</td>
    <td>{{ number_format($debt['debtAmount'], 2, ',', ' ') }}</td>
    <td>{{ number_format($debt['paidAmount'], 2, ',', ' ') }}</td>
    <td>{{ number_format($debt['saldo'], 2, ',', ' ') }}</td>
    <td>
      @foreach ($debt['paids'] as $paid)
        @if($paid['amount'])
          {{ $paid['date'] }} - {{ number_format($paid['amount'], 2, ',', ' ') }} руб. - {{ $paid['desc'] }};
        @endif
      @endforeach
    </td>
    <td>2</td>
    <td>{{ $debt['name'] }}</td>
    <td>Списки по зарплате</td>
  </tr>
  @endforeach
</table>