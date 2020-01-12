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
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Сформировать отчет 
        </h3>
        <form method="post" action="/admin/reports" class="d-inline">
          {{ csrf_field() }}          
          @foreach(config('app.reportableTables') as $model)
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="inputSwitch_{{ $model }}" name="{{ $model }}">
                <label class="custom-control-label" for="inputSwitch_{{ $model }}">{{ trans("messages.tables.$model.name") }}</label>
              </div>
          @endforeach          
          <button type="submit" class="btn btn-primary">Сгенерировать</button>
        </form>
    </div>
@endsection
