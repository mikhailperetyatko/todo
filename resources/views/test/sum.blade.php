@php
require '/var/www/todo/resources/views/test/sum.php';

function noise($text) {
  $map = [
    'А' => ['A'],
    'а' => ['a'],
    'О' => ['O'],
    'о' => ['o'],
    'Е' => ['E'],
    'е' => ['e'],
    'В' => ['B'],
    'К' => ['K'],
    'Т' => ['T'],
    'Н' => ['H'],
    'С' => ['C'],
    'с' => ['c'],
    'М' => ['M'],
    'Р' => ['P'],
    'р' => ['p'],
  ];
  
  $result = '';
  for ($i = 0; $i < mb_strlen($text); $i++) {
    $word = mb_substr($text, $i, 1);
    $result .= isset($map[$word]) ? (rand(0, 1) ? $map[$word][array_rand($map[$word])] : $word) : $word;
  }
  return $result;
}

@endphp

<table border="1" style="border-collapse:collapse">
  @foreach($mass as $name)
    <tr>
      <td>{{ noise($name['name']) }}</td>
    </tr>
  @endforeach
</table>