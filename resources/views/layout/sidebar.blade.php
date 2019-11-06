@php 
    $tagsCloud = $tagsCloud ?? collect();
@endphp
<aside class="col-md-4 blog-sidebar">
   <div class="p-3">
    <h4 class="font-italic">Tags</h4>
    @include('posts.tags', ['tags' => $tagsCloud])
  </div>
</aside>
