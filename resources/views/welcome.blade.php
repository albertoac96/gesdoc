<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        

        <title>Mi sistema</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

         <script src="{{ asset('js/app.js') }}" defer></script>
         <link href="{{ asset('css/app.css') }}" rel="stylesheet">
          <script src="https://kit.fontawesome.com/e813ff6fee.js" crossorigin="anonymous"></script>
    
         <script>
            @auth
                window.Permissions = {!! json_encode(Auth::user()->allPermissions, true) !!};
                window.NameUser = {!! json_encode(Auth::user()->name, true) !!};
                window.Mail = {!! json_encode(Auth::user()->email, true) !!};
            @else
                window.Permissions = [];
            @endauth
        </script>
    </head>
    <body>
       <div id="app">
            <app></app>
       </div>
    </body>
</html>
