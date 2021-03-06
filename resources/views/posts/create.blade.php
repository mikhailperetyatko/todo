@extends('layout.without_sidebar')
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
        <form method="post" action="/posts">
          
          {{ csrf_field() }}
          
          <div class="form-group">
            <label for="inputLink">Символьный код</label>
            <input type="text" class="form-control" id="inputTitle" placeholder="Введите символьный код" name="slug" value="{{ old('slug') ?? '' }}" required>
          </div>
          <div class="form-group">
            <label for="inputTitle">Название статьи</label>
            <input type="text" class="form-control" id="inputTitle" placeholder="Введите название" name="title" value="{{ old('title') ?? '' }}" required>
          </div>
          <div class="form-group">
            <label for="inputDescription">Краткое описание</label>
            <textarea type="text" class="form-control" id="inputDescription" rows="3" name="description" required>{{ old('description') ?? '' }}</textarea>
          </div>
          <div class="form-group">
            <label for="inputDescription">Текст статьи</label>
            <textarea type="text" class="form-control" id="inputDescription" rows="10" name="body" required>{{ old('body') ?? '' }}</textarea>
          </div>
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="inputSwitch" name="published" {{old('published') ? 'checked' : ''}}>
            <label class="custom-control-label" for="inputSwitch">Опубликовать</label>
          </div>
          <div class="form-group">
            <label for="inputTitle">Тэги</label>
            <input type="text" 
                class="form-control" 
                id="inputTags" 
                placeholder="Введите название" 
                name="tags" 
            >
          </div>
          <button type="submit" class="btn btn-primary">Добавить</button>
        </form>
    </div>

@endsection