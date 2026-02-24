<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OMDB Premium Cinema')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;900&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-black': '#0a0a0a',
                        'secondary-black': '#1a1a1a',
                        'gold': '#d4af37',
                        'gold-light': '#f4e4b8',
                        'gold-dark': '#b8941f',
                        'accent-gold': '#ffd700',
                    }
                }
            }
        }
    </script>
    <!-- Bootstrap CSS (untuk grid dan utility saja) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-black: #0a0a0a;
            --secondary-black: #1a1a1a;
            --gold: #d4af37;
            --gold-light: #f4e4b8;
            --gold-dark: #b8941f;
            --accent-gold: #ffd700;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0f0f0f 100%);
            min-height: 100vh;
            font-family: 'Montserrat', sans-serif;
            color: #e0e0e0;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 50%, rgba(212, 175, 55, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(212, 175, 55, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .navbar {
            background: linear-gradient(180deg, rgba(10, 10, 10, 0.98) 0%, rgba(26, 26, 26, 0.95) 100%) !important;
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(212, 175, 55, 0.1);
            border-bottom: 2px solid rgba(212, 175, 55, 0.3);
            padding: 1.2rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar>.container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--gold) !important;
            text-shadow: 0 0 20px rgba(212, 175, 55, 0.5);
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: translateY(-2px);
            text-shadow: 0 0 30px rgba(212, 175, 55, 0.8);
        }

        .navbar-brand svg {
            margin-right: 12px;
            filter: drop-shadow(0 0 10px rgba(212, 175, 55, 0.4));
        }

        .nav-link {
            color: #e0e0e0 !important;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem !important;
            display: flex;
            align-items: center;
            border-radius: 8px;
        }

        .nav-link svg {
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--gold) !important;
            background: rgba(212, 175, 55, 0.1);
        }

        .nav-link:hover svg {
            transform: scale(1.1);
        }

        .nav-link.active {
            color: var(--gold) !important;
            background: rgba(212, 175, 55, 0.15);
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.2);
        }

        /* Base styles for navbar collapse */
        .navbar-collapse {
            flex-grow: 1;
            align-items: center;
        }

        .navbar-nav {
            display: flex;
            gap: 0.5rem;
        }

        .card {
            background: linear-gradient(145deg, rgba(26, 26, 26, 0.9) 0%, rgba(20, 20, 20, 0.95) 100%);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5), 0 0 20px rgba(212, 175, 55, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 40px rgba(212, 175, 55, 0.3);
            border-color: var(--gold);
        }

        .card:hover::before {
            opacity: 1;
        }

        .movie-poster {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 20px 20px 0 0;
            background: linear-gradient(145deg, #1a1a1a, #0f0f0f);
            transition: transform 0.4s ease;
        }

        .movie-poster.lazy {
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .movie-poster.lazy.loaded {
            opacity: 1;
        }

        .btn-favorite {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(10, 10, 10, 0.9);
            border: 2px solid var(--gold);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
            backdrop-filter: blur(10px);
        }

        .btn-favorite:hover {
            background: var(--gold);
            transform: scale(1.15) rotate(10deg);
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.6);
        }

        .btn-favorite svg {
            width: 24px;
            height: 24px;
            transition: all 0.3s ease;
        }

        .btn-favorite.active svg {
            fill: var(--gold);
        }

        .loading-spinner {
            text-align: center;
            padding: 40px;
            display: none;
        }

        .loading-spinner .spinner-border {
            border-color: var(--gold);
            border-right-color: transparent;
            width: 3rem;
            height: 3rem;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #e0e0e0;
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            margin: 0 auto 30px;
            display: block;
            opacity: 0.4;
        }

        .empty-state h3 {
            font-family: 'Playfair Display', serif;
            color: var(--gold);
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .search-form {
            background: linear-gradient(145deg, rgba(26, 26, 26, 0.95) 0%, rgba(20, 20, 20, 0.98) 100%);
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5), 0 0 20px rgba(212, 175, 55, 0.1);
            margin-bottom: 40px;
            border: 1px solid rgba(212, 175, 55, 0.2);
            backdrop-filter: blur(20px);
        }

        .search-form h4 {
            font-family: 'Playfair Display', serif;
            color: var(--gold);
            font-weight: 600;
            letter-spacing: 1px;
        }

        .form-control,
        .form-select {
            background: rgba(10, 10, 10, 0.8);
            border: 2px solid rgba(212, 175, 55, 0.3);
            color: #e0e0e0;
            padding: 12px 20px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(10, 10, 10, 0.95);
            border-color: var(--gold);
            color: #e0e0e0;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
        }

        .form-control::placeholder {
            color: rgba(224, 224, 224, 0.5);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold) 100%);
            border: none;
            color: #0a0a0a;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(212, 175, 55, 0.3);
            letter-spacing: 0.5px;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(212, 175, 55, 0.5);
            color: #0a0a0a;
        }

        .btn-outline-danger {
            border: 2px solid rgba(220, 53, 69, 0.5);
            color: #e0e0e0;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-danger:hover {
            background: rgba(220, 53, 69, 0.9);
            border-color: #dc3545;
            color: white;
        }

        .language-switcher {
            display: flex;
            gap: 8px;
        }

        .language-switcher a {
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            color: #e0e0e0;
            border: 1px solid rgba(212, 175, 55, 0.3);
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .language-switcher a.active {
            background: var(--gold);
            color: #0a0a0a;
            border-color: var(--gold);
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.4);
        }

        .language-switcher a:hover:not(.active) {
            border-color: var(--gold);
            color: var(--gold);
        }

        /* Navbar Toggler (Hamburger) Styling */
        .navbar-toggler {
            border: 2px solid var(--gold);
            padding: 8px 12px;
            border-radius: 8px;
            background: rgba(212, 175, 55, 0.1);
            transition: all 0.3s ease;
        }

        .navbar-toggler:hover {
            background: rgba(212, 175, 55, 0.2);
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.3);
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.25);
        }

        .navbar-toggler-icon {
            width: 24px;
            height: 24px;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23d4af37' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            filter: none !important;
        }

        /* Navbar Responsive */
        @media (max-width: 991.98px) {

            /* Force Bootstrap collapse to work properly */
            .navbar-collapse {
                background: rgba(26, 26, 26, 0.98);
                border-radius: 15px;
                padding: 1rem;
                margin-top: 1rem;
                border: 1px solid rgba(212, 175, 55, 0.2);
                backdrop-filter: blur(10px);
                position: relative;
                z-index: 1050;
                width: 100%;
            }

            /* Bootstrap collapse states - explicit override */
            .navbar-collapse:not(.show) {
                display: none !important;
                visibility: hidden;
            }

            .navbar-collapse.show {
                display: block !important;
                visibility: visible !important;
            }

            .navbar-collapse.collapsing {
                height: 0;
                overflow: hidden;
                transition: height 0.35s ease;
                visibility: visible !important;
            }

            .nav-link {
                margin: 0.5rem 0;
            }

            .navbar-text {
                margin: 0.5rem 0 !important;
            }

            .language-switcher {
                margin: 1rem 0;
            }

            .d-flex.align-items-center.flex-wrap {
                flex-direction: column !important;
                align-items: flex-start !important;
            }
        }

        @media (min-width: 992px) {

            /* Desktop: always show navbar */
            .navbar-collapse {
                display: flex !important;
                justify-content: space-between;
                visibility: visible !important;
                flex-basis: auto;
                flex-grow: 1;
            }

            .navbar-nav {
                flex-direction: row;
                align-items: center;
            }

            .navbar-toggler {
                display: none !important;
            }

            .navbar>.container {
                flex-wrap: nowrap;
            }
        }

        .card-body {
            background: linear-gradient(145deg, rgba(26, 26, 26, 0.7) 0%, rgba(15, 15, 15, 0.9) 100%);
            padding: 20px;
        }

        .card-title {
            font-family: 'Playfair Display', serif;
            color: var(--gold);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .card-text {
            color: rgba(224, 224, 224, 0.7);
        }

        .btn-sm {
            font-size: 0.85rem;
            padding: 8px 20px;
            border-radius: 10px;
            font-weight: 600;
        }

        main {
            position: relative;
            z-index: 1;
        }

        /* Premium Toast Notifications */
        .premium-toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        }

        .premium-toast {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.98) 0%, rgba(10, 10, 10, 0.98) 100%);
            border: 2px solid var(--gold);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.8), 0 0 30px rgba(212, 175, 55, 0.3);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            gap: 15px;
            animation: slideInRight 0.4s ease-out;
            position: relative;
            overflow: hidden;
        }

        .premium-toast::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
        }

        .premium-toast.success {
            border-color: var(--gold);
        }

        .premium-toast.error {
            border-color: #dc3545;
        }

        .premium-toast.error::before {
            background: linear-gradient(90deg, transparent, #dc3545, transparent);
        }

        .premium-toast-icon {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(212, 175, 55, 0.1);
        }

        .premium-toast.error .premium-toast-icon {
            background: rgba(220, 53, 69, 0.1);
        }

        .premium-toast-content {
            flex: 1;
            color: #e0e0e0;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .premium-toast-close {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0.6;
            transition: all 0.3s ease;
        }

        .premium-toast-close:hover {
            opacity: 1;
            transform: rotate(90deg);
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .premium-toast.removing {
            animation: slideOutRight 0.3s ease-in forwards;
        }

        /* Poster Placeholder */
        .poster-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0a0a0a 0%, #000000 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .poster-placeholder::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, rgba(212, 175, 55, 0.05) 0%, transparent 70%);
            pointer-events: none;
        }

        .poster-placeholder-content {
            color: rgba(212, 175, 55, 0.8);
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.4rem;
            line-height: 1.5;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 10px rgba(212, 175, 55, 0.3);
            position: relative;
            z-index: 1;
        }

        /* Premium Confirmation Dialog */
        .premium-confirm-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            opacity: 0;
            animation: fadeIn 0.3s ease forwards;
        }

        .premium-confirm-dialog {
            background: linear-gradient(145deg, rgba(26, 26, 26, 0.98) 0%, rgba(20, 20, 20, 0.98) 100%);
            border: 2px solid rgba(212, 175, 55, 0.3);
            border-radius: 20px;
            padding: 40px;
            max-width: 450px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8), 0 0 30px rgba(212, 175, 55, 0.15);
            transform: scale(0.9);
            animation: scaleIn 0.3s ease forwards;
        }

        .premium-confirm-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle, rgba(220, 53, 69, 0.15), transparent);
            border-radius: 50%;
            border: 2px solid rgba(220, 53, 69, 0.4);
        }

        .premium-confirm-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--gold);
            text-align: center;
            margin-bottom: 15px;
        }

        .premium-confirm-message {
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            color: rgba(224, 224, 224, 0.85);
            text-align: center;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .premium-confirm-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .premium-confirm-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 12px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 120px;
        }

        .premium-confirm-btn-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        .premium-confirm-btn-danger:hover {
            background: linear-gradient(135deg, #c82333, #bd2130);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
            transform: translateY(-2px);
        }

        .premium-confirm-btn-secondary {
            background: rgba(224, 224, 224, 0.1);
            color: rgba(224, 224, 224, 0.9);
            border: 1px solid rgba(224, 224, 224, 0.2);
        }

        .premium-confirm-btn-secondary:hover {
            background: rgba(224, 224, 224, 0.15);
            border-color: rgba(224, 224, 224, 0.3);
            transform: translateY(-2px);
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            to {
                transform: scale(1);
            }
        }

        .premium-confirm-overlay.closing {
            animation: fadeOut 0.3s ease forwards;
        }

        .premium-confirm-overlay.closing .premium-confirm-dialog {
            animation: scaleOut 0.3s ease forwards;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
            }
        }

        @keyframes scaleOut {
            to {
                transform: scale(0.9);
            }
        }
    </style>

    @yield('styles')
</head>

<body>
    <!-- Navbar -->
    @auth
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('movies.index') }}">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 2L25.878 13.764L38.637 15.708L29.318 24.764L31.755 37.472L20 31.236L8.245 37.472L10.682 24.764L1.363 15.708L14.122 13.764L20 2Z" fill="url(#gold-gradient)" stroke="#d4af37" stroke-width="2" />
                    <defs>
                        <linearGradient id="gold-gradient" x1="20" y1="2" x2="20" y2="38" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#ffd700" />
                            <stop offset="100%" stop-color="#b8941f" />
                        </linearGradient>
                    </defs>
                </svg>
                {{ __('messages.app_name') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('movies.*') ? 'active' : '' }}" href="{{ route('movies.index') }}">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2" />
                                <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            {{ __('messages.browse_movies') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('favorites.*') ? 'active' : '' }}" href="{{ route('favorites.index') }}">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                <path d="M12 21.35L10.55 20.03C5.4 15.36 2 12.27 2 8.5C2 5.41 4.42 3 7.5 3C9.24 3 10.91 3.81 12 5.08C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.41 22 8.5C22 12.27 18.6 15.36 13.45 20.03L12 21.35Z" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                            {{ __('messages.my_favorites') }}
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center flex-wrap gap-3">
                    <div class="language-switcher">
                        <a href="{{ route('language.switch', 'en') }}" class="{{ session('locale', 'en') === 'en' ? 'active' : '' }}">EN</a>
                        <a href="{{ route('language.switch', 'id') }}" class="{{ session('locale', 'en') === 'id' ? 'active' : '' }}">ID</a>
                    </div>

                    <span class="navbar-text d-flex align-items-center" style="color: #e0e0e0; margin: 0;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                            <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2" />
                            <path d="M6 21V19C6 16.7909 7.79086 15 10 15H14C16.2091 15 18 16.7909 18 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        {{ Auth::user()->name }}
                    </span>

                    <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-1">
                                <path d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                <path d="M16 17L21 12L16 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            {{ __('messages.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Setup CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Handle navbar visibility - ONLY for desktop, leave mobile to Bootstrap
        $(document).ready(function() {
            const $navbarContent = $('#navbarContent');
            const $toggler = $('.navbar-toggler');

            function isDesktop() {
                return $(window).width() >= 992;
            }

            function updateNavbar() {
                if (isDesktop()) {
                    // Desktop: force always visible
                    $navbarContent.addClass('show').css('display', 'flex');
                }
                // Mobile: do absolutely nothing - let Bootstrap handle everything
            }

            // Set initial state
            updateNavbar();

            // Debug: Log when hamburger is clicked
            $toggler.on('click', function(e) {
                console.log('=== Hamburger Clicked ==>');
                console.log('Before - Has .show class:', $navbarContent.hasClass('show'));
                console.log('Before - Display:', $navbarContent.css('display'));
                console.log('Before - Height:', $navbarContent.height());

                setTimeout(function() {
                    console.log('After - Has .show class:', $navbarContent.hasClass('show'));
                    console.log('After - Display:', $navbarContent.css('display'));
                    console.log('After - Height:', $navbarContent.height());
                    console.log('After - Visibility:', $navbarContent.css('visibility'));
                    console.log('After - Computed style:', window.getComputedStyle($navbarContent[0]).display);
                }, 350);
            });

            // Handle window resize
            let resizeTimer;
            $(window).on('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(updateNavbar, 200);
            });

            // Initialize lazy load
            lazyLoadImages();
        });

        // Poster error handler - only replace if actual image fails, not loading placeholder
        function handlePosterError(img, title) {
            // Prevent infinite error loop
            img.onerror = null;

            // Check if this is still the loading placeholder
            if (img.src.includes('via.placeholder.com')) {
                // Still loading, don't replace yet
                return;
            }

            // Create placeholder element using DOM API
            const placeholderDiv = document.createElement('div');
            placeholderDiv.className = 'movie-poster';

            const posterPlaceholder = document.createElement('div');
            posterPlaceholder.className = 'poster-placeholder';

            const content = document.createElement('div');
            content.className = 'poster-placeholder-content';
            content.innerHTML = `
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin: 0 auto 15px; opacity: 0.4;">
                    <rect x="2" y="3" width="20" height="18" rx="2" stroke="#d4af37" stroke-width="1.5"/>
                    <circle cx="8.5" cy="8.5" r="1.5" fill="#d4af37" opacity="0.5"/>
                    <path d="M2 17L8 11L12 15L22 5" stroke="#d4af37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.5"/>
                </svg>
                <div>${title}</div>
            `;

            posterPlaceholder.appendChild(content);
            placeholderDiv.appendChild(posterPlaceholder);

            // Replace the image with placeholder
            if (img.parentNode) {
                img.parentNode.replaceChild(placeholderDiv, img);
            }
        }

        function handlePosterErrorDetail(img, title) {
            // Prevent infinite error loop
            img.onerror = null;

            // Create placeholder element
            const placeholder = document.createElement('div');
            placeholder.style.cssText = 'width: 100%; aspect-ratio: 2/3; background: linear-gradient(135deg, #0a0a0a 0%, #000000 100%); display: flex; align-items: center; justify-content: center; border-radius: 20px; padding: 40px; position: relative; overflow: hidden;';

            placeholder.innerHTML = `
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: radial-gradient(circle at center, rgba(212, 175, 55, 0.05) 0%, transparent 70%); pointer-events: none;"></div>
                <div style="text-align: center; color: rgba(212, 175, 55, 0.8); position: relative; z-index: 1;">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin: 0 auto 25px; opacity: 0.4;">
                        <rect x="2" y="3" width="20" height="18" rx="2" stroke="#d4af37" stroke-width="1.5"/>
                        <circle cx="8.5" cy="8.5" r="1.5" fill="#d4af37" opacity="0.5"/>
                        <path d="M2 17L8 11L12 15L22 5" stroke="#d4af37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.5"/>
                    </svg>
                    <div style="font-family: 'Playfair Display', serif; font-weight: 700; font-size: 1.5rem; line-height: 1.5; letter-spacing: 0.5px; text-shadow: 0 2px 10px rgba(212, 175, 55, 0.3);">${title}</div>
                </div>
            `;

            // Replace the image with placeholder
            if (img.parentNode) {
                img.parentNode.replaceChild(placeholder, img);
            }
        }

        // Premium Confirmation Dialog
        function showConfirm(title, message, onConfirm, onCancel) {
            const overlay = $(`
                <div class="premium-confirm-overlay">
                    <div class="premium-confirm-dialog">
                        <div class="premium-confirm-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" stroke="#dc3545" stroke-width="2"/>
                                <path d="M12 8V12M12 16H12.01" stroke="#dc3545" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="premium-confirm-title">${title}</div>
                        <div class="premium-confirm-message">${message}</div>
                        <div class="premium-confirm-buttons">
                            <button class="premium-confirm-btn premium-confirm-btn-secondary" id="confirmCancel">Cancel</button>
                            <button class="premium-confirm-btn premium-confirm-btn-danger" id="confirmOk">Delete</button>
                        </div>
                    </div>
                </div>
            `);

            $('body').append(overlay);

            $('#confirmOk').on('click', function() {
                closeConfirm(overlay, onConfirm);
            });

            $('#confirmCancel').on('click', function() {
                closeConfirm(overlay, onCancel);
            });

            overlay.on('click', function(e) {
                if ($(e.target).hasClass('premium-confirm-overlay')) {
                    closeConfirm(overlay, onCancel);
                }
            });
        }

        function closeConfirm(overlay, callback) {
            overlay.addClass('closing');
            setTimeout(() => {
                overlay.remove();
                if (callback) callback();
            }, 300);
        }

        // Premium Toast notification helper
        function showToast(message, type = 'success') {
            // Create container if not exists
            if (!$('.premium-toast-container').length) {
                $('body').append('<div class="premium-toast-container"></div>');
            }

            const iconSvg = type === 'success' ?
                `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 7L9 18L4 13" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                   </svg>` :
                `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="#dc3545" stroke-width="2"/>
                    <path d="M12 8V12M12 16H12.01" stroke="#dc3545" stroke-width="2" stroke-linecap="round"/>
                   </svg>`;

            const toast = `
                <div class="premium-toast ${type}">
                    <div class="premium-toast-icon">${iconSvg}</div>
                    <div class="premium-toast-content">${message}</div>
                    <div class="premium-toast-close" onclick="closeToast(this)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 6L6 18M6 6L18 18" stroke="#888" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
            `;

            $('.premium-toast-container').append(toast);

            // Auto remove after 5 seconds
            const $toast = $('.premium-toast-container .premium-toast').last();
            setTimeout(() => {
                $toast.addClass('removing');
                setTimeout(() => $toast.remove(), 300);
            }, 5000);
        }

        function closeToast(element) {
            const $toast = $(element).closest('.premium-toast');
            $toast.addClass('removing');
            setTimeout(() => $toast.remove(), 300);
        }

        // Lazy load images
        function lazyLoadImages() {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img.lazy').forEach(img => {
                imageObserver.observe(img);
            });
        }

        // Initialize lazy load on page load
        $(document).ready(function() {
            lazyLoadImages();
        });
    </script>

    @yield('scripts')
</body>

</html>
