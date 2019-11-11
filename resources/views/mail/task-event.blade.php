@component('mail::message')
    Событие: {{ $event::NAME }} <br />
    Заголовок статьи: {{ $post->title }} <br />   
    @if($event::SHOW_LINK)
        @component('mail::button', ['url' => '/posts/' . $post->slug])
            Перейти к статье
        @endcomponent
    @endif
    {{ config('app.name') }}
@endcomponent
