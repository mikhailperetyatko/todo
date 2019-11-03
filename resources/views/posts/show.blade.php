@extends('layout.master')
    
@section('content')
    <div class="col-md-8 blog-main">
        <a href="/"><-Вернуться назад</a>
        <br />
        <a class="btn btn-secondary" href="/posts/{{ $data->slug }}/edit">Изменить</a>
        <div class="blog-post">
            <h2 class="blog-post-title">
                {{ $data->title }}
            </h2>
            <p class="blog-post-meta">Статья добавлена на сайт: {{ $data->created_at->toDayDateTimeString() }}</p>
            <p>{{ $data->body }} </p>
        </div>
    </div>
@endsection
