@extends('layout.without_sidebar')

@section('content')
    <div class="container">
        <h5>
            Добавление нового хранилища
        </h5>
        <form method="post" action="/home/storages">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="inputName">Название хранилища</label>
                <input type="text" class="form-control {{ $errors->get('name') ? 'is-invalid' : '' }}" id="inputName" name="name" placeholder="Введите название" value="{{ old('name') ?? '' }}" required>
                <div class="invalid-feedback">
                    {{ implode(',', $errors->get('name') ?? []) }}
                </div>
            </div>
            <div class="form-group">
                <label for="textareaDescription">Описание</label>
                <textarea class="form-control {{ $errors->get('description') ? 'is-invalid' : '' }}" id="textareaDescription" rows="3" name="description">{{ old('description') ?? '' }}</textarea>
                <div class="invalid-feedback">
                    {{ implode(',', $errors->get('description') ?? []) }}
                </div>
            </div>
            
            <button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapseProjectsShare" aria-expanded="false" aria-controls="collapseProjectsShare">
                Использовать со следующими проектами
            </button>
            <div class="collapse" id="collapseProjectsShare">
                <div class="card card-body">
                    @foreach($user->projects as $key => $project)
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch{{ $key }}" name="projectsToSync[{{ $project->id }}]" {{ old('projectsToSync')[$project->id] ? 'checked' : '' }}>
                            <label class="custom-control-label" for="customSwitch{{ $key }}">{{ $project->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label for="storageSelect">Тип хпанилища</label>
                <select class="form-control" id="storageSelect" name="type">
                    @foreach(config('app.storages') as $value)
                        <option value="{{ $value['value'] }}">{{ $value['name'] }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="dropdown-divider"></div>
            <button type="submit" class="btn btn-primary">Создать</button>
        </form>
    </div>
@endsection