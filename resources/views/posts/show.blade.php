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
