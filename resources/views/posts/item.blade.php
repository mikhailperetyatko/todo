<div class="blog-post">
    <h2 class="blog-post-title">
        <a href="@getLinkForManagePost($post)">{{ $post->title }}</a>
    </h2>
    <p class="blog-post-meta">{{ $post->created_at->toFormattedDateString() }}</p>
    <p>{{ $post->description }} </p>
    @include('tags', ['tags' => $post->tags])
</div>