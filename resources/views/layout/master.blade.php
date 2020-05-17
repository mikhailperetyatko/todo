<!doctype html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    
    <!-- Custom styles for this template -->
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900" rel="stylesheet">
    <link href="/css/blog.css" rel="stylesheet">
  </head>

  <body class="d-flex flex-column h-100">
    @include('layout.nav')
    
    <main role="main" class="mb-2" id="app">
        <div aria-live="polite" class="fixed-top" aria-atomic="true">
            <div style="position: absolute; top: 0; right: 0;">
                <div class="container focus">
                    @if(auth()->check())
                        <toasts :user="{{ auth()->user()->id }}" :data='@json(auth()->user()->unreadNotifications)'>Подождите...</toasts>
                    @endif
                    @yield('vueExt') 
                </div>            
            </div>
        </div>
        <div class="container">
            @include('layout.notification')
        </div>
        <div class="container">
            <div class="row">
                
                @yield('content')
                
                @section('sidebar')
                    @include('layout.sidebar')
                @show
                
            </div>
        </div>
    </main>
    @include('layout.footer')
    <script src="/js/app.js?v={{ 4 }}"></script>
  </body>
</html>
