<div class="container">
    <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
          <div class="col-4 pt-1">
          </div>
          <div class="col-4 text-center">
            <a class="blog-header-logo text-dark" href="/">MyBlog</a>
          </div>
          <div class="col-4 d-flex justify-content-end align-items-center">
            @guest
                <a class="btn btn-outline-secondary mr-1 btn-sm" href="{{ route('login') }}">Войти</a> 
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('register') }}">Зарегистрироваться</a>
            @else
                <div class="dropdown">
                    <a href="#" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item btn btn-sm" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                            Выйти
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            @endguest
          </div>
        </div>
    </header>
    <div class="nav-scroller py-1 mb-2 border-bottom">
        <nav class="nav d-flex justify-content-between">
          <a class="p-2 text-muted" href="/">Главная</a>
          <a class="p-2 text-muted" href="/about">О нас</a>
          <a class="p-2 text-muted" href="/contacts">Контакты</a>
          <a class="p-2 text-muted" href="/posts/create">Создать статью</a>
          <a class="p-2 text-muted" href="/informations">Новости</a>
          <a class="p-2 text-muted" href="/statistics">Статистика</a>
          @can('administrate')
            <a class="p-2 text-muted" href="/admin">Административный раздел</a>
          @endcan
        </nav>
    </div>
</div>
