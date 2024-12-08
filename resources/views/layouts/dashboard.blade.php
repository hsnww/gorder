<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc." />

    <!-- Light/dark mode -->
    <script>
        /*!
         * Color mode toggler for Bootstrap's docs (https://getbootstrap.com/)
         * Copyright 2011-2024 The Bootstrap Authors
         * Licensed under the Creative Commons Attribution 3.0 Unported License.
         * Modified by Simpleqode
         */

        (() => {
            'use strict';

            const getStoredTheme = () => localStorage.getItem('theme');
            const setStoredTheme = (theme) => localStorage.setItem('theme', theme);

            const getPreferredTheme = () => {
                const storedTheme = getStoredTheme();
                if (storedTheme) {
                    return storedTheme;
                }

                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            };

            const setTheme = (theme) => {
                if (theme === 'auto') {
                    document.documentElement.setAttribute('data-bs-theme', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                } else {
                    document.documentElement.setAttribute('data-bs-theme', theme);
                }
            };

            setTheme(getPreferredTheme());

            const showActiveTheme = (theme, focus = false) => {
                const themeSwitchers = document.querySelectorAll('[data-bs-theme-switcher]');

                themeSwitchers.forEach((themeSwitcher) => {
                    const themeSwitcherIcon = themeSwitcher.querySelector('.material-symbols-outlined');
                    themeSwitcherIcon.innerHTML = theme === 'light' ? 'light_mode' : theme === 'dark' ? 'dark_mode' : 'contrast';

                    if (focus) {
                        themeSwitcher.focus();
                    }
                });

                document.querySelectorAll('[data-bs-theme-value]').forEach((element) => {
                    element.classList.remove('active');
                    element.setAttribute('aria-pressed', 'false');

                    if (element.getAttribute('data-bs-theme-value') === theme) {
                        element.classList.add('active');
                        element.setAttribute('aria-pressed', 'true');
                    }
                });
            };

            const refreshCharts = () => {
                const charts = document.querySelectorAll('.chart-canvas');

                charts.forEach((chart) => {
                    const chartId = chart.getAttribute('id');
                    const instance = Chart.getChart(chartId);

                    if (!instance) {
                        return;
                    }

                    if (instance.options.scales.y) {
                        instance.options.scales.y.grid.color = getComputedStyle(document.documentElement).getPropertyValue('--bs-border-color');
                        instance.options.scales.y.ticks.color = getComputedStyle(document.documentElement).getPropertyValue('--bs-secondary-color');
                    }

                    if (instance.options.scales.x) {
                        instance.options.scales.x.ticks.color = getComputedStyle(document.documentElement).getPropertyValue('--bs-secondary-color');
                    }

                    if (instance.options.elements.arc) {
                        instance.options.elements.arc.borderColor = getComputedStyle(document.documentElement).getPropertyValue('--bs-body-bg');
                        instance.options.elements.arc.hoverBorderColor = getComputedStyle(document.documentElement).getPropertyValue('--bs-body-bg');
                    }

                    instance.update();
                });
            };

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                const storedTheme = getStoredTheme();
                if (storedTheme !== 'light' && storedTheme !== 'dark') {
                    setTheme(getPreferredTheme());
                }
            });

            window.addEventListener('DOMContentLoaded', () => {
                showActiveTheme(getPreferredTheme());

                document.querySelectorAll('[data-bs-theme-value]').forEach((toggle) => {
                    toggle.addEventListener('click', (e) => {
                        e.preventDefault();
                        const theme = toggle.getAttribute('data-bs-theme-value');
                        setStoredTheme(theme);
                        setTheme(theme);
                        showActiveTheme(theme, true);
                        refreshCharts();
                    });
                });
            });
        })();
    </script>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('') }}backend/assets/favicon/favicon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0" />

    <!-- Libs CSS -->
    <link rel="stylesheet" href="{{ asset('') }}backend/assets/css/libs.bundle.css" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('') }}backend/assets/css/theme.bundle.css" />

    <!-- Title -->
    <title>Dashbrd</title>
</head>

<body>
<!-- Sidenav -->
<!-- Sidenav (sm) -->
<aside class="aside aside-sm d-none d-xl-flex">
    <nav class="navbar navbar-expand-xl navbar-vertical">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="sidenavSmallCollapse">
                <!-- Nav -->
                <nav class="navbar-nav nav-pills h-100">
                    <div class="nav-item">
                        <div data-bs-toggle="tooltip" data-bs-placement="right" data-bs-trigger="hover" data-bs-title="Color mode">
                            <a
                                class="nav-link"
                                data-bs-toggle="collapse"
                                data-bs-theme-switcher
                                href="#colorModeOptions"
                                role="button"
                                aria-expanded="false"
                                aria-controls="colorModeOptions"
                            >
                                <span class="material-symbols-outlined mx-auto"> </span>
                            </a>
                        </div>
                        <div class="collapse" id="colorModeOptions">
                            <div class="border-top border-bottom py-2">
                                <a
                                    class="nav-link fs-sm"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="right"
                                    data-bs-trigger="hover"
                                    data-bs-title="Light"
                                    data-bs-theme-value="light"
                                    href="#"
                                    role="button"
                                >
                                    <span class="material-symbols-outlined mx-auto"> light_mode </span>
                                </a>
                                <a
                                    class="nav-link fs-sm"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="right"
                                    data-bs-trigger="hover"
                                    data-bs-title="Dark"
                                    data-bs-theme-value="dark"
                                    href="#"
                                    role="button"
                                >
                                    <span class="material-symbols-outlined mx-auto"> dark_mode </span>
                                </a>
                                <a
                                    class="nav-link fs-sm"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="right"
                                    data-bs-trigger="hover"
                                    data-bs-title="Auto"
                                    data-bs-theme-value="auto"
                                    href="#"
                                    role="button"
                                >
                                    <span class="material-symbols-outlined mx-auto"> contrast </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="hover" data-bs-title="Go to product page">
                        <a class="nav-link" href="https://themes.getbootstrap.com/product/dashbrd/" target="_blank">
                            <span class="material-symbols-outlined mx-auto"> local_mall </span>
                        </a>
                    </div>
                    <div class="nav-item mt-auto" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="hover" data-bs-title="Contact us">
                        <a class="nav-link" href="mailto:yevgenysim+simpleqode@gmail.com">
                            <span class="material-symbols-outlined mx-auto"> support </span>
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </nav>
</aside>

<!-- Sidenav (lg) -->
<aside class="aside">
    <nav class="navbar navbar-expand-xl navbar-vertical">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand fs-5 fw-bold px-xl-3 mb-xl-4" href="{{ route('home') }}">
                <i class="fs-4 text-secondary me-1" data-duoicon="box-2"></i> Dashbrd
            </a>

            <!-- User -->
            <div class="ms-auto d-xl-none">
                <div class="dropdown my-n2">
                    <a class="btn btn-link d-inline-flex align-items-center dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="avatar avatar-sm avatar-status avatar-status-success me-3">
                <img class="avatar-img" src="{{ asset('') }}backend/assets/img/photos/photo-6.jpg" alt="..." />
            </span>
                        {{ auth()->user()->name ?? 'Guest' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Account</a></li>
                        <li><a class="dropdown-item" href="#">Change password</a></li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Sign out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Toggler -->
            <button
                class="navbar-toggler ms-3"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#sidenavLargeCollapse"
                aria-controls="sidenavLargeCollapse"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenavLargeCollapse">
                <!-- Search -->
                <div class="input-group d-xl-none my-4 my-xl-0">
                    <input
                        class="form-control"
                        id="topnavSearchInputMobile"
                        type="search"
                        placeholder="Search"
                        aria-label="Search"
                        aria-describedby="navbarSearchMobile"
                    />
                    <span class="input-group-text" id="navbarSearchMobile">
                <span class="material-symbols-outlined"> search </span>
              </span>
                </div>

                <!-- Nav -->
                <nav class="navbar-nav nav-pills mb-7">
                    <div class="nav-item">
                        <a
                            class="nav-link active"
                            href="#"
                            data-bs-toggle="collapse"
                            data-bs-target="#dashboards"
                            rol="button"
                            aria-expanded="false"
                            aria-controls="dashboards"
                        >
                            <span class="material-symbols-outlined me-3">space_dashboard</span> Dashboards
                        </a>
                        <div class="collapse show" id="dashboards">
                            <nav class="nav nav-pills">
                                <a class="nav-link active" href="./index.html">Default</a>
                                <a class="nav-link " href="{{ route('admin.products.index') }}">Products</a>
                                <a class="nav-link " href="{{ route('admin.categories.index') }}">Categories</a>
                            </nav>
                        </div>
                    </div>
                    <div class="nav-item">
                        <a
                            class="nav-link "
                            href="#"
                            data-bs-toggle="collapse"
                            data-bs-target="#customers"
                            rol="button"
                            aria-expanded="false"
                            aria-controls="customers"
                        >
                            <span class="material-symbols-outlined me-3">group</span> Customers
                        </a>
                        <div class="collapse " id="customers">
                            <nav class="nav nav-pills">
                                <a class="nav-link " href="./customers/customers.html">Customers</a>
                                <a class="nav-link " href="./customers/customer.html">Customer details</a>
                                <a class="nav-link " href="./customers/customer-new.html">New customer</a>
                            </nav>
                        </div>
                    </div>
                    <div class="nav-item">
                        <a
                            class="nav-link "
                            href="#"
                            data-bs-toggle="collapse"
                            data-bs-target="#projects"
                            rol="button"
                            aria-expanded="false"
                            aria-controls="projects"
                        >
                            <span class="material-symbols-outlined me-3">list_alt</span> Projects
                        </a>
                        <div class="collapse " id="projects">
                            <nav class="nav nav-pills">
                                <a class="nav-link " href="./projects/projects.html">Projects</a>
                                <a class="nav-link " href="./projects/project.html">Project overview</a>
                                <a class="nav-link " href="./projects/project-new.html">New project</a>
                            </nav>
                        </div>
                    </div>
                    <div class="nav-item">
                        <a
                            class="nav-link "
                            href="#"
                            data-bs-toggle="collapse"
                            data-bs-target="#account"
                            rol="button"
                            aria-expanded="false"
                            aria-controls="account"
                        >
                            <span class="material-symbols-outlined me-3">person</span> Account
                        </a>
                        <div class="collapse " id="account">
                            <nav class="nav nav-pills">
                                <a class="nav-link " href="./account/account.html">Account overview</a>
                                <a class="nav-link " href="./account/account-settings.html">Account settings</a>
                            </nav>
                        </div>
                    </div>
                    <div class="nav-item">
                        <a
                            class="nav-link "
                            href="#"
                            data-bs-toggle="collapse"
                            data-bs-target="#orders"
                            rol="button"
                            aria-expanded="false"
                            aria-controls="orders"
                        >
                            <span class="material-symbols-outlined me-3">shopping_cart</span> Orders
                        </a>
                        <div class="collapse " id="orders">
                            <nav class="nav nav-pills">
                                <a class="nav-link " href="./orders/orders.html">Orders</a>
                                <a class="nav-link " href="./orders/invoice.html">Invoice</a>
                                <a class="nav-link " href="./orders/pricing.html">Pricing</a>
                            </nav>
                        </div>
                    </div>
                    <div class="nav-item">
                        <a
                            class="nav-link "
                            href="#"
                            data-bs-toggle="collapse"
                            data-bs-target="#posts"
                            rol="button"
                            aria-expanded="false"
                            aria-controls="posts"
                        >
                            <span class="material-symbols-outlined me-3">text_fields</span> Posts
                        </a>
                        <div class="collapse " id="posts">
                            <nav class="nav nav-pills">
                                <a class="nav-link " href="./posts/categories.html">Categories</a>
                                <a class="nav-link " href="./posts/posts.html">Posts</a>
                                <a class="nav-link " href="./posts/post-new.html">New post</a>
                            </nav>
                        </div>
                    </div>
                    <div class="nav-item">
                        <a
                            class="nav-link"
                            href="#"
                            data-bs-toggle="collapse"
                            data-bs-target="#authentication"
                            rol="button"
                            aria-expanded="false"
                            aria-controls="authentication"
                        >
                            <span class="material-symbols-outlined me-3">login</span> Authentication
                        </a>
                        <div class="collapse" id="authentication">
                            <nav class="nav nav-pills">
                                <a class="nav-link" href="./auth/sign-in.html">Sign in</a>
                                <a class="nav-link" href="./auth/sign-up.html">Sign up</a>
                                <a class="nav-link" href="./auth/password-reset.html">Password reset</a>
                                <a class="nav-link" href="./auth/error.html">Error</a>
                            </nav>
                        </div>
                    </div>
                </nav>

                <!-- Heading -->
                <h3 class="fs-base px-3 mb-4">Documentation</h3>

                <!-- Nav -->
                <nav class="navbar-nav nav-pills mb-xl-7">
                    <div class="nav-item">
                        <a class="nav-link " href="./docs/getting-started.html">
                            <span class="material-symbols-outlined me-3">sticky_note_2</span> Getting started
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link " href="./docs/components.html">
                            <span class="material-symbols-outlined me-3">deployed_code</span> Components
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link " href="./docs/changelog.html">
                            <span class="material-symbols-outlined me-3">list_alt</span> Changelog
                            <span class="badge text-bg-primary ms-auto">1.0.3</span>
                        </a>
                    </div>
                </nav>

                <!-- Divider -->
                <hr class="my-4 d-xl-none" />

                <!-- Nav -->
                <nav class="navbar-nav nav-pills d-xl-none mb-7">
                    <div class="nav-item">
                        <a
                            class="nav-link"
                            data-bs-toggle="collapse"
                            data-bs-theme-switcher
                            href="#colorModeOptionsMobile"
                            role="button"
                            aria-expanded="false"
                            aria-controls="colorModeOptionsMobile"
                        >
                            <span class="material-symbols-outlined me-3"> </span> Color mode
                        </a>
                        <div class="collapse" id="colorModeOptionsMobile">
                            <div class="nav nav-pills">
                                <a class="nav-link" data-bs-theme-value="light" href="#" role="button"> Light </a>
                                <a class="nav-link" data-bs-theme-value="dark" href="#" role="button"> Dark </a>
                                <a class="nav-link" data-bs-title="Auto" data-bs-theme-value="auto" href="#" role="button"> Auto </a>
                            </div>
                        </div>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link" href="https://themes.getbootstrap.com/product/dashbrd/" target="_blank">
                            <span class="material-symbols-outlined me-3">local_mall</span> Go to product page
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link" href="mailto:yevgenysim+simpleqode@gmail.com">
                            <span class="material-symbols-outlined me-3">alternate_email</span> Contact us
                        </a>
                    </div>
                </nav>

                <!-- Card -->
                <div class="card mt-auto">
                    <div class="card-body">
                        <!-- Heading -->
                        <h6>Need help?</h6>

                        <!-- Text -->
                        <p class="text-body-secondary mb-0">Feel free to reach out to us should you have any questions or suggestions.</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</aside>

<!-- Topnav -->
<nav class="navbar d-none d-xl-flex px-xl-6">
    <div class="container flex-column align-items-stretch">
        <div class="row">
            <div class="col">
                <!-- Search -->
                <div class="input-group" style="max-width: 400px">
                    <input class="form-control" id="topnavSearchInput" type="search" placeholder="Search" aria-label="Search" aria-describedby="navbarSearch" />
                    <span class="input-group-text" id="navbarSearch">
                <kbd class="badge bg-body-secondary text-body">⌘</kbd>
                <kbd class="badge bg-body-secondary text-body ms-1">K</kbd>
              </span>
                </div>
            </div>
            <div class="col-auto">
                <!-- User -->
                <div class="dropdown my-n2">
                    <a class="btn btn-link d-inline-flex align-items-center dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="avatar avatar-sm avatar-status avatar-status-success me-3">
            <img class="avatar-img" src="{{ asset('') }}backend/assets/img/photos/photo-6.jpg" alt="..." />
        </span>
                        {{ auth()->user()->name ?? 'Guest' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Account</a></li>
                        <li><a class="dropdown-item" href="#">Change password</a></li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();">
                                Sign out
                            </a>
                            <form id="logout-form-desktop" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</nav>

<!-- Main -->
<main class="main px-lg-6">
    <!-- Content -->
    <div class="container-lg">
        <!-- Page content -->
        <div class="row align-items-center">
            <div class="col-12 col-md-auto order-md-1 d-flex align-items-center justify-content-center mb-4 mb-md-0">
                <div class="avatar text-info me-2">
                    <i class="fs-4" data-duoicon="world"></i>
                </div>
                San Francisco, CA –&nbsp;<time datetime="20:00">8:00 PM</time>
            </div>
            <div class="col-12 col-md order-md-0 text-center text-md-start">
                <h1>Hello,  {{ auth()->user()->name ?? 'Guest' }}</h1>
                <p class="fs-lg text-body-secondary mb-0">Here's a summary of your account activity for this week.</p>
            </div>
        </div>

        <!-- Divider -->
        <hr class="my-8" />
        @yield('content')

    </div>
</main>

<!-- JAVASCRIPT -->
<!-- Map JS -->
<script src='https://api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.js'></script>

<!-- Vendor JS -->
<script src="{{ asset('') }}backend/assets/js/vendor.bundle.js"></script>

<!-- Theme JS -->
<script src="{{ asset('') }}backend/assets/js/theme.bundle.js"></script>
</body>
</html>