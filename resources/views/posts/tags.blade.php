@php
    $tags = $tags ?? collect()
@endphp
@if($tags->isNotEmpty())
    <ul class="nav">
        @foreach($tags as $tag)
            <li class="nav-item m-1"><a class="badge badge-secondary badge-pill" href="/posts/tags/{{ $tag->name }}">{{ $tag->name }}</a></li>
        @endforeach
    </ul>
 @endif