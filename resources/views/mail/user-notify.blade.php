@component('mail::message')
    Уважаемый пользователь! За прошлую неделю были опубликованы следующие статьи: <br>
    <ul>
    @foreach($posts as $post)
        <li>
            <a href="{{ config('app.url') }}/posts/{{ $post->slug }}">{{$post->title}}</a>
        </li>
    @endforeach
    </ul>
@endcomponent
