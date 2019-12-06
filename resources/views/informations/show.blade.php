@extends('layout.master')

@section('content')
    <div class="col-md-8 blog-main">
        <br />
        @if(auth()->check() && auth()->user()->isAdmin())
            <a href="@getLinkForManageNews($information)/edit" class="btn btn-outline-secondary btn-sm">Изменить</a>
        @endif
        
        <div class="blog-post">
            <h2 class="blog-post-title">
                {{ $information->title }}
            </h2>
            <p class="blog-post-meta">Новость добавлена на сайт: {{ $information->created_at->toDayDateTimeString() }}</p>
            <p>{{ $information->body }} </p>
            @include('tags', ['tags' => $information->tags])
            
            <hr />
            @if(auth()->check())
                <form method="post" action="/informations/{{ $information->slug }}/comments">
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
