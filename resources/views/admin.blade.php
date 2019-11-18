@extends('layout.without_sidebar', ['admin_panel' => true])
    
@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Административный раздел 
        </h3>
        <ul>
            <li><a href="/admin/feedbacks">Обращения</a></li>
            <li><a href="/admin/posts">Статьи</a></li>
        </ul>
    </div>
@endsection
