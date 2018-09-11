<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
    .active {
        border-bottom: #00b5e2 solid 2.5px;
    } 
    .nav-item {
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
    }
    </style>
    @yield('style')
</head>
<body>
    <div id="app">        
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel py-0">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item @yield('products-nav')">
                            <a class="nav-link" href="/products">Products <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item @yield('shopping-cart-nav')">
                            <a class="nav-link" href="/products/cart">Shopping Cart <span class="sr-only">(current)</span></a>
                        </li>   
                        @auth                            
                            <li class="nav-item @yield('order-list-nav')">
                                <a class="nav-link" href="/products/checkout">Order List <span class="sr-only">(current)</span></a>
                            </li>   
                        @endauth
                        <li class="nav-item @yield('post-nav')">
                            <a class="nav-link" href="/posts">Post <span class="sr-only">(current)</span></a>
                        </li>
                        @auth                            
                            <li class="nav-item @yield('chat-room-nav')">
                                <a class="nav-link" href="/chat">Chat Room <span class="sr-only">(current)</span></a>
                            </li>   
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item @yield('login-nav')">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item @yield('register-nav')">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @else
                            <li class="nav-item @yield('user-admin-nav')"><a class="nav-link" href="/user">User 管理</a></li>
                            <li class="nav-item @yield('user-home-nav') dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>    

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('script')
</body>
</html>
