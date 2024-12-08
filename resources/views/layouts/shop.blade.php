<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" {{ app()->getLocale() === 'ar' ? 'dir=rtl' : '' }}>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>{{ env('APP_TITLE') }}</title>
        <!-- Favicon-->
        <link rel="icon" type="{{ asset('') }}image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{ asset('') }}css/styles.css" rel="stylesheet" />

    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="{{ route('home') }}">{{ env('APP_NAME') }}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('home') }}">
                                {{ __('menu.home') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#!">{{ __('menu.about') }}</a>
                            <!-- إضافة عنصر جديد لعرض صفحة المزودين -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('providers.index') }}">
                                {{ __('menu.providers') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('categories.index') }}">
                                {{ __('categories.list') }}
                            </a>
                        </li>                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('menu.shop') }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="#!">{{ __('menu.All Products') }}</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#!">{{ __('menu.Popular Items') }}</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#!">{{ __('menu.New Arrivals') }}</a>
                                </li>
                            </ul>
                        </li>

                    </ul>

                    <ul class="navbar-nav mb-2 mb-lg-0">
                        @guest
                            <!-- إذا كان المستخدم ضيف -->
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{__('menu.login')}}</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">{{__('menu.register')}}</a></li>
                        @else
                            <!-- إذا كان المستخدم مسجل الدخول -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="{{ route('dashboard') }}">{{ __('menu.dashboard') }}</a></li>
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('menu.logout')}}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest

                    </ul>
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-dark">
                        <i class="bi-cart-fill me-1"></i>
                        {{ __('labels.cart') }}
                        <span class="badge bg-dark text-white ms-1 rounded-pill">
        {{ session()->has('cart') ? count(session('cart')) : 0 }}
    </span>
                    </a>



                </div>

            </div>
            <ul class="navbar-nav mb-2 mb-lg-0">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="languageDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-globe me-1"></i> {{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ url()->current() }}?lang=en">
                                <i class="bi bi-flag me-1"></i> English
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ url()->current() }}?lang=ar">
                                <i class="bi bi-flag-fill me-1"></i> العربية
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- Header-->
        <header class="bg-dark py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">{{__('labels.site name')}}</h1>
                    <p class="lead fw-normal text-white-50 mb-0">{{__('labels.site description')}} </p>
                </div>
            </div>
        </header>
        <!-- Section-->
        @yield('main')

        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="{{ asset('') }}js/scripts.js"></script>
    </body>
</html>
