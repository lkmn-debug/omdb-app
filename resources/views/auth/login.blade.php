@extends('layouts.app')

@section('title', __('messages.login'))

@section('styles')
<style>
    .auth-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    .auth-card {
        background: linear-gradient(135deg, rgba(26, 26, 26, 0.95) 0%, rgba(10, 10, 10, 0.98) 100%);
        border: 2px solid var(--gold);
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5),
            0 0 40px rgba(212, 175, 55, 0.1);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .auth-card:hover {
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.6),
            0 0 60px rgba(212, 175, 55, 0.2);
        border-color: var(--accent-gold);
    }

    .auth-logo {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        display: block;
    }

    .auth-title {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--gold);
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 10px rgba(212, 175, 55, 0.3);
    }

    .auth-subtitle {
        color: var(--gold-light);
        font-size: 0.95rem;
        margin-bottom: 2rem;
    }

    .form-label {
        color: var(--gold-light);
        font-weight: 500;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .auth-input-group {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .auth-input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        pointer-events: none;
    }

    .auth-input {
        width: 100%;
        padding: 14px 16px 14px 48px;
        background: rgba(26, 26, 26, 0.6);
        border: 2px solid rgba(212, 175, 55, 0.3);
        border-radius: 12px;
        color: #ffffff;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .auth-input:focus {
        outline: none;
        border-color: var(--gold);
        background: rgba(26, 26, 26, 0.8);
        box-shadow: 0 0 20px rgba(212, 175, 55, 0.2);
    }

    .auth-input::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }

    .auth-input.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .form-check {
        margin-bottom: 1.5rem;
    }

    .form-check-input {
        width: 20px;
        height: 20px;
        border: 2px solid rgba(212, 175, 55, 0.4);
        background: rgba(26, 26, 26, 0.6);
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: var(--gold);
        border-color: var(--gold);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.25);
    }

    .form-check-label {
        color: var(--gold-light);
        margin-left: 0.5rem;
        cursor: pointer;
    }

    .auth-button {
        width: 100%;
        padding: 14px 24px;
        background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
        border: 2px solid var(--gold);
        border-radius: 12px;
        color: #000000;
        font-weight: 600;
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .auth-button:hover {
        background: linear-gradient(135deg, var(--accent-gold) 0%, var(--gold) 100%);
        border-color: var(--accent-gold);
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.4);
    }

    .auth-button:active {
        transform: translateY(0);
    }

    .test-credentials {
        margin-top: 2rem;
        padding: 1rem;
        background: rgba(212, 175, 55, 0.1);
        border: 1px solid rgba(212, 175, 55, 0.2);
        border-radius: 10px;
        color: var(--gold-light);
        font-size: 0.9rem;
        text-align: center;
    }

    .test-credentials strong {
        color: var(--gold);
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="auth-container">
                <div class="auth-card">
                    <div class="text-center">
                        <!-- Premium Logo SVG -->
                        <svg class="auth-logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="logo-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#ffd700;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#d4af37;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <polygon points="50,10 61,40 92,40 67,59 78,89 50,70 22,89 33,59 8,40 39,40"
                                fill="url(#logo-gradient)" stroke="#ffd700" stroke-width="2" />
                            <circle cx="50" cy="50" r="15" fill="none" stroke="#ffd700" stroke-width="2" opacity="0.5" />
                        </svg>

                        <h3 class="auth-title">{{ __('messages.app_name') }}</h3>
                        <p class="auth-subtitle">{{ __('messages.login_subtitle') }}</p>
                    </div>

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="username" class="form-label">{{ __('messages.username') }}</label>
                            <div class="auth-input-group">
                                <!-- User Icon SVG -->
                                <svg class="auth-input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="#d4af37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <circle cx="12" cy="7" r="4" stroke="#d4af37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <input type="text"
                                    class="auth-input @error('username') is-invalid @enderror"
                                    id="username"
                                    name="username"
                                    value="{{ old('username') }}"
                                    required
                                    autofocus
                                    placeholder="{{ __('messages.enter_username') }}">
                            </div>
                            @error('username')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">{{ __('messages.password') }}</label>
                            <div class="auth-input-group">
                                <!-- Lock Icon SVG -->
                                <svg class="auth-input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="#d4af37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="#d4af37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <input type="password"
                                    class="auth-input @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    required
                                    placeholder="{{ __('messages.enter_password') }}">
                            </div>
                            @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">{{ __('messages.remember_me') }}</label>
                        </div>

                        <button type="submit" class="auth-button">
                            <!-- Login Icon SVG -->
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <polyline points="10 17 15 12 10 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <line x1="15" y1="12" x2="3" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            {{ __('messages.login') }}
                        </button>
                    </form>

                    <div class="test-credentials">
                        {{ __('messages.test_credentials') }}<br>
                        <strong>Username:</strong> aldmic | <strong>Password:</strong> 123abc123
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection