@php
    $comments = $comments ?? collect();
@endphp
<div class="container">
    @if($comments->isNotEmpty())
        <h5>
            Комментарии:
        </h5>
        @foreach($comments as $comment)
            @include('comments.item', ['comment' => $comment])
        @endforeach
        {{ $comments->render() }}
    @endif
</div>
