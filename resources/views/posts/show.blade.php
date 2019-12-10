@extends('layout.master')

@section('content')
    <div class="col-md-8 blog-main">
        <br />
        @can('update', $post)
            <a href="@getLinkForManagePost($post)/edit" class="btn btn-outline-secondary btn-sm">Изменить</a>
        @endcan
        
        <div class="blog-post">
            <h2 class="blog-post-title">
                {{ $post->title }}
            </h2>
            <p class="blog-post-meta">Статья добавлена на сайт: {{ $post->created_at->toDayDateTimeString() }}</p>
            <p>{{ $post->body }} </p>
            @include('tags', ['tags' => $post->tags])
            <hr />
            <h5>История изменений</h5>
            @if($post->history->isNotEmpty())
                <table class="table">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col">Пользователь</th>
                      <th scope="col">Дата изменения</th>
                      <th scope="col">Изменения</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($post->history as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->pivot->created_at->diffForHumans() }}</td>
                            <td>
                                <table>
                                    <tr>
                                        <th>Поле</th>
                                        <th>Было</th>
                                        <th>Стало</th>
                                    </tr>
                                    @foreach($item->pivot->before as $key => $change)  
                                        <tr>
                                            <td>@getNameOfPostAttributeInRussian($key)</td>
                                            <td>{{ $change }}</td>
                                            <td>{{ $item->pivot->after->$key }}</td>
                                        </tr>
                                        <p></p>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    @endforeach
                  </tbody>
                </table>
            @else
                <p>Истории нет</p>
            
            @endif
                        
            <hr />
            @if(auth()->check())
                <form method="post" action="/posts/{{ $post->slug }}/comments">
                  {{ csrf_field() }}
                  <div class="form-group">
                    <label for="inputBody">Ваш новый комментарий</label>
                    <textarea type="text" class="form-control" id="inputBody" rows="5" name="body" required></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary">Комментировать</button>
                </form>
            @else
                <p>
                    Для добавления комментария - авторизируйтесь.
                </p>
            @endif        
            @include('comments.list', [
                'comments' => $comments,
            ])
        </div>
    </div>
@endsection
