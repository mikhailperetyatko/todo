@php 
    $tagsCloud = $tagsCloud ?? collect();
@endphp
<aside class="col-md-4 blog-sidebar">
   <div class="p-3">
    @if(Request::is('admin/*'))
        @include('layout.nav_auth')
    @else
        <h4 class="font-italic">Tags</h4>
        @include('posts.tags', ['tags' => $tagsCloud])
    @endif
  </div>
</aside>
