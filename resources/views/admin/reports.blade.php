@extends('layout.master')
    
@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Сформировать отчет 
        </h3>
        <form method="post" action="/admin/reports" class="d-inline">
          {{ csrf_field() }}          
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="inputSwitch_informations" name="informations">
            <label class="custom-control-label" for="inputSwitch_informations">Новости</label>
          </div>
          
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="inputSwitch_posts" name="posts">
            <label class="custom-control-label" for="inputSwitch_posts">Статьи</label>
          </div>
          
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="inputSwitch_comments" name="comments">
            <label class="custom-control-label" for="inputSwitch_comments">Комментарии</label>
          </div>
          
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="inputSwitch_tags" name="tags">
            <label class="custom-control-label" for="inputSwitch_tags">Теги</label>
          </div>
          
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="inputSwitch_users" name="users">
            <label class="custom-control-label" for="inputSwitch_users">Пользователи</label>
          </div>
          
          <button type="submit" class="btn btn-primary">Сгенерировать</button>
        </form>
    </div>
@endsection
