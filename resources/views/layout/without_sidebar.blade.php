@extends('layout.master')
@section('sidebar')
@can('administrate')
    <ul>
        <li><a href="/admin/feedbacks">Обращения</a></li>
        <li><a href="/admin/posts">Статьи</a></li>
    </ul>
@endcan
@endsection