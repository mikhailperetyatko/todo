@extends('layout.master')
    
@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Статьи и Новости
        </h3>
        @foreach ($items as $key => $val)
            <h4><u>{{ $key == 'posts' ? 'Статьи' : 'Новости' }}</u></h4>
            @foreach ($val as $item)
                @include($key . '.item', [$key == 'posts' ? 'post' : 'information' => $item])
            @endforeach
            {{ $val->render() }}
            <hr />
        @endforeach
    </div>
@endsection
