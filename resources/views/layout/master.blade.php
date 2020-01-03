<!doctype html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MyBlog</title>
    <!-- Bootstrap core CSS -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900" rel="stylesheet">
    <link href="/css/blog.css" rel="stylesheet">
  </head>

  <body class="d-flex flex-column h-100">
    
    @include('layout.nav')
    
    <main role="main" class="mb-2">
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
    <div aria-live="polite" class="fixed-top" aria-atomic="true" id="app">
        <div style="position: absolute; top: 0; right: 0;">
            <div class="container focus">
                <post-update></post-update>
                @if($_SERVER['REQUEST_URI'] == '/admin/reports')
                    <report-generated user="{{ auth()->user()->id }}"></report-generated>
                @endif     
            </div>            
        </div>
    </div>
    @include('layout.footer')
    <script src="/js/app.js"></script>
  </body>
</html>
