@extends('layout.master')
    
@section('content')
    <div class="col-md-8 blog-main">
        <a href="/"><-Вернуться назад</a>
        <div class="blog-post">
            <h2 class="blog-post-title">
                {{ $slug->title }}
            </h2>
            <p class="blog-post-meta">Статья добавлена на сайт: {{ $slug->created_at->toDayDateTimeString() }}</p>
            <p>{{ $slug->body }} </p>
        </div>
    </div>
@endsection
