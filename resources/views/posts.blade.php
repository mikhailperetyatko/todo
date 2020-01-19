@extends('layout.master')
    
@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Статьи 
        </h3>
        @foreach ($posts as $post)
            @include('posts.item')
        @endforeach
        {{ $posts->render() }}
    </div>
@endsection
