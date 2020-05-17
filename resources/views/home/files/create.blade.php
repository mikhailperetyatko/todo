@extends('layout.without_sidebar')

@section('content')
<file :subtask='{{ $subtask->id }}' :checkbox="true">Подождите...</file>
@endsection
