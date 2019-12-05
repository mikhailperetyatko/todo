<div class="blog-post">
    <h2 class="blog-post-title">
        <a href="@getLinkForManageNews($information)">{{ $information->title }}</a>
    </h2>
    <p class="blog-post-meta">{{ $information->created_at->toFormattedDateString() }}</p>
    @include('tags', ['tags' => $information->tags])
</div>