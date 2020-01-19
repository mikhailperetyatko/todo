@extends('layout.without_sidebar')

@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Статистика 
        </h3>
        <p>Общее количество статей - {{ $amountPosts ?? '' }}</p>
        <p>Общее количество новостей - {{ $amountInformations ?? '' }} </p>
        <p>ФИО автора, у которого больше всего статей на сайте - 
            @if(! is_null($userWhoHasTheMostPosts))
            {{ $userWhoHasTheMostPosts->name }}
            @else
                отсутствует
            @endif
        </p>
        <p>
            Самая длинная статья - 
            @if(! is_null($biggestPost))
                <a href="/posts/{{ $biggestPost->slug }}">Статья "{{ $biggestPost->title }}" длинной {{$biggestPost->post_length}} символов</a>
            @else
                отсутствует
            @endif
        </p>
        <p>
            Самая короткая статья - 
            @if(! is_null($smallerPost))
            <a href="/posts/{{ $smallerPost->slug }}">Статья "{{ $smallerPost->title }}" длинной {{$smallerPost->post_length}} символов</a>
            @else
                отсутствует
            @endif
        </p>
        <p>Средние количество статей у активных пользователей - {{ $averagePostsOfActiveUsers }} </p>
        <p>
            Самая непостоянная - 
            @if(! is_null($mostChangeable))
            <a href="/posts/{{ $mostChangeable->slug }}">Статья "{{ $mostChangeable->title }}", которую изменяли {{$mostChangeable->amount_changes}} раз(а)</a>
            @else
                отсутствует
            @endif
        </p>
        <p>
            Самая обсуждаемая статья  - 
            @if(! is_null($mostCommentable))
            <a href="/posts/{{ $mostCommentable->slug }}">Статья "{{ $mostCommentable->title }}", которую комментировали {{$mostCommentable->amount_comments}} раз(а)</a>
            @else
                отсутствует
            @endif
        </p>
    </div>
@endsection
