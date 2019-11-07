@extends('layout.without_sidebar')
    
@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Обращения 
        </h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Email</th>
                    <th scope="col">Сообщение</th>
                    <th scope="col">Дата получения</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $feedback)
                <tr>
                    <td scope="row">{{ $feedback->email }}</td>
                    <td scope="row">{{ $feedback->body }}</td>
                    <td scope="row">{{ $feedback->created_at->toFormattedDateString() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $data->render() }}
    </div>
@endsection
