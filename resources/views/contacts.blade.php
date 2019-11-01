@extends('layout.master')
    
@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Контакты 
        </h3>
        <p>По всем возникающим вопросам звоните по телефону 8-800-123-45-67 или оставьте свое обращение здесь:</p>
        <div class="container">
            @if($errors->count())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="post" action="/feedbacks">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="inputEmail">email</label>
                <input type="email" class="form-control" id="inputEmail" placeholder="Ваш email для обратно связи" name="email" value="{{ old('email') ?? '' }}" required>
              </div>
              <div class="form-group">
                <label for="inputPetition">Краткое описание</label>
                <textarea type="text" class="form-control" id="inputPetition" rows="3" name="body" required>{{ old('body') ?? '' }}</textarea>
              </div>
              <button type="submit" class="btn btn-primary">Отправить</button>
            </form>
        </div>
    </div>
@endsection
