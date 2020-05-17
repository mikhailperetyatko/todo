@extends('layout.without_sidebar')

@section('content')
<script>
    window.location.href = window.location.href.replace('#', '?');
</script>
@endsection
