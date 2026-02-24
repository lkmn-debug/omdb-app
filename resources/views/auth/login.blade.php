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

    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        z-index: 10;
        background: none;
        border: none;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .password-toggle:hover svg {
        stroke: var(--gold);
    }

    .password-input {
        padding-right: 48px !important;
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
                                    class="auth-input password-input @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    required
                                    placeholder="{{ __('messages.enter_password') }}">
                                <!-- Toggle Password Visibility Button -->
                                <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle password visibility">
                                    <!-- Eye Icon (Show) -->
                                    <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="#d4af37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <circle cx="12" cy="12" r="3" stroke="#d4af37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <!-- Eye Off Icon (Hide) -->
                                    <svg id="eyeOffIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" stroke="#d4af37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <line x1="1" y1="1" x2="23" y2="23" stroke="#d4af37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        if (togglePassword && password && eyeIcon && eyeOffIcon) {
            togglePassword.addEventListener('click', function() {
                // Toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle the eye icons
                if (type === 'password') {
                    eyeIcon.style.display = 'block';
                    eyeOffIcon.style.display = 'none';
                } else {
                    eyeIcon.style.display = 'none';
                    eyeOffIcon.style.display = 'block';
                }
            });
        }
    });
</script>
@endsection
