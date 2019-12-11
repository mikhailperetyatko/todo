@extends('layout.master')
@section('content')
    <div class="col-md-8 blog-main">
        @if($errors->count())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="post" action="/admin/informations/{{ $information->slug }}" class="d-inline">
          
          {{ csrf_field() }}
          {{ method_field('PATCH') }}
          
          <div class="form-group">
            <label for="inputLink">Символьный код</label>
            <input type="text" class="form-control" id="inputTitle" placeholder="Введите символьный код" name="slug" value="{{ old('slug', $information->slug) ?? '' }}" required>
          </div>
          <div class="form-group">
            <label for="inputTitle">Название новости</label>
            <input type="text" class="form-control" id="inputTitle" placeholder="Введите название" name="title" value="{{ old('title', $information->title) ?? '' }}" required>
          </div>
            <div class="form-group">
            <label for="inputDescription">Текст новости</label>
            <textarea type="text" class="form-control" id="inputDescription" rows="10" name="body" required>{{ old('body', $information->body) ?? '' }}</textarea>
          </div>
          <div class="form-group">
            <label for="inputTitle">Тэги</label>
            <input type="text" 
                class="form-control" 
                id="inputTags" 
                placeholder="Введите название" 
                name="tags" 
                value="{{ old('tags', $information->tags->pluck('name')->implode(',')) ?? '' }}"
            >
          </div>
          <button type="submit" class="btn btn-primary">Изменить</button>
        </form>
        <form class="mt-2 d-inline" method="post" action="/admin/informations/{{$information->slug}}">
          {{ csrf_field() }}
          {{ method_field('DELETE') }}
          <button type="submit" class="btn btn-danger">Удалить</button>
        </form>
    </div>

@endsection