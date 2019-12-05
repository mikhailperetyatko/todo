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
        </div>
    </div>
@endsection
