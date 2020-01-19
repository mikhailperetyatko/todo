@component('mail::message')
    Событие: {{ $event }} <br />
    Заголовок статьи: {{ $post->title }} <br />   
    @if($showLink)
        @component('mail::button', ['url' => config('app.url') . '/posts/' . $post->slug])
            Перейти к статье
        @endcomponent
    @endif
    {{ config('app.name') }}
@endcomponent
