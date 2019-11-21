@extends('layout.master')

@section('content')
    <div class="col-md-8 blog-main">
        <br />
        @can('update', $post)
            <a href="@prefix()/posts/{{ $post->slug }}/edit" class="btn btn-outline-secondary btn-sm">Изменить</a>
        @endcan
        
        <div class="blog-post">
            <h2 class="blog-post-title">
                {{ $post->title }}
            </h2>
            <p class="blog-post-meta">Статья добавлена на сайт: {{ $post->created_at->toDayDateTimeString() }}</p>
            <p>{{ $post->body }} </p>
            @include('posts.tags', ['tags' => $post->tags])
        </div>
    </div>
@endsection
