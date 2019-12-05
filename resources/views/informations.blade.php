@extends('layout.master')
    
@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Новости 
        </h3>
        @foreach ($informations as $information)
            @include('informations.item')
        @endforeach
        {{ $informations->render() }}
    </div>
@endsection
