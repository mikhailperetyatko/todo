<nav class="blog-pagination">
    <button class="btn btn-outline-secondary" {{ $prevPage == $page ? 'disabled' : '' }} href="/{{ $prefix }}" rel="prev" data-page="{{ $prevPage }}" data-role="paginatorGet"><-</button>
    <button class="btn btn-outline-secondary" {{ $nextPage == $page ? 'disabled' : '' }} href="/{{ $prefix }}" rel="next" data-page="{{ $nextPage }}" data-role="paginatorGet">-></button>
</nav>
<script>
window.addEventListener('load', function() {
    $('[data-role=paginatorGet]:enabled').on('click', function(){
        $(location).attr('href', $(this).attr('href') + '/page/' + $(this).attr('data-page'));
    });
});
</script>
