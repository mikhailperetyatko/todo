@extends('layout.master')

@section('content')
    <div class="col-md-8 blog-main">
        <a href="/"><-Вернуться назад</a>
        <br />
        <a class="btn btn-secondary" href="/posts/{{ $post->slug }}/edit">Изменить</a>
        <div class="blog-post">
            <h2 class="blog-post-title">
                {{ $post->title }}
            </h2>
            <p class="blog-post-meta">Статья добавлена на сайт: {{ $post->created_at->toDayDateTimeString() }}</p>
            <p>{{ $post->body }} </p>
        </div>
    </div>
@endsection
