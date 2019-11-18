<a class="btn btn-outline-secondary btn-sm" href="/posts/{{ $post->slug }}/edit">Изменить</a>
<form class="mt-2 d-inline" method="post" action="/posts/{{$post->slug}}">
  {{ csrf_field() }}
  {{ method_field('DELETE') }}
  <button type="submit" class="btn btn-outline-danger btn-sm">Удалить</button>
</form>