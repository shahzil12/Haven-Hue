<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Haven&Hue') }}</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand text-accent fw-bold" href="{{ url('/') }}">
                    Haven&Hue
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Home</a>
                        </li>
                        @php
                            try {
                                $adminExists = \App\Models\User::where('role', 'admin')->exists();
                            } catch (\Illuminate\Database\QueryException $e) {
                                $adminExists = false;
                            }
                        @endphp
                        @if(!$adminExists && Auth::check())
                             <li class="nav-item">
                                <a class="nav-link text-danger fw-bold" href="{{ route('setup.admin') }}">CLAIM ADMIN</a>
                            </li>
                        @endif
                        @auth
                            <li class="nav-item">
                                <span class="nav-link text-warning fw-bold">Role: {{ ucfirst(Auth::user()->role) }}</span>
                            </li>
                            @if(Auth::user()->role === 'buyer')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('orders.index') }}">My Orders</a>
                                </li>
                            @endif
                            @if(Auth::user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Pannel</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                                Cart
                                @auth
                                    <span class="badge bg-secondary-custom text-dark rounded-pill">{{ Auth::user()->cart->sum('quantity') }}</span>
                                @endauth
                            </a>
                        </li>
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
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
            @if(session('success'))
                <div class="container">
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="container">
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                </div>
            @endif
            @yield('content')
        </main>
        
        <footer class="bg-black text-white py-5 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <h5 class="text-uppercase mb-3">About Haven&Hue</h5>
                        <p class="small text-white mb-0">
                            Haven&Hue is your premier destination for exquisite home decor. We believe in transforming spaces into sanctuaries with our curated collection of furniture, lighting, and accessories. Our mission is to help you express your unique style through high-quality, sustainable, and beautiful design.
                        </p>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <h5 class="text-uppercase mb-3">Quick Links</h5>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none">Shop</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none">About Us</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none">Contact</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-uppercase mb-3">Contact</h5>
                        <ul class="list-unstyled text-white-50">
                            <li><i class="fas fa-map-marker-alt me-2"></i> Wah Cantt, Pakistan</li>
                            <li><i class="fas fa-phone me-2"></i> +92 319 5291829</li>
                            <li><i class="fas fa-envelope me-2"></i> info@havenandhue.com</li>
                        </ul>
                    </div>
                </div>
                <hr class="my-4 border-secondary">
                <div class="text-center text-white-50">
                    <small>&copy; {{ date('Y') }} Haven&Hue. All rights reserved.</small>
                </div>
            </div>
        </footer>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    @stack('scripts')
</body>
</html>
