<div class="card mt-2 bg-light">
  <div class="card-header">
    {{ $comment->owner->name }}, {{ $comment->created_at->toFormattedDateString() }}
  </div>
  <div class="card-body">
    <p class="ml-2">
      {{ $comment->body }}
    </p>
  </div>
</div>