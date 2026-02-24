@extends('layouts.app')

@section('title', __('messages.login') . ' — Monitorly')

@section('styles')
<style>
    .auth-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 40px 24px;
    }

    /* Grid bg */
    .auth-page::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(0, 255, 136, 0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 255, 136, 0.02) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    .auth-page::after {
        content: '';
        position: absolute;
        top: -30%;
        left: 50%;
        transform: translateX(-50%);
        width: 700px;
        height: 700px;
        background: radial-gradient(circle, rgba(0, 255, 136, 0.06) 0%, transparent 70%);
    }

    .auth-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 48px;
        width: 100%;
        max-width: 460px;
        position: relative;
        z-index: 10;
        box-shadow: var(--shadow), 0 0 80px rgba(0, 255, 136, 0.03);
    }

    .auth-logo {
        text-align: center;
        margin-bottom: 32px;
    }
    .auth-logo a {
        font-family: var(--font-display);
        font-size: 1.6rem;
        font-weight: 800;
        text-decoration: none;
        background: var(--gradient-1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: 3px;
    }
    .auth-title {
        font-family: var(--font-display);
        font-size: 1.3rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 8px;
        letter-spacing: 1px;
    }
    .auth-subtitle {
        text-align: center;
        color: var(--text-secondary);
        font-size: 0.95rem;
        margin-bottom: 32px;
    }

    .auth-footer {
        text-align: center;
        margin-top: 24px;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }
    .auth-footer a {
        color: var(--accent);
        text-decoration: none;
        font-weight: 600;
    }
    .auth-footer a:hover { text-decoration: underline; }

    .auth-lang {
        text-align: center;
        margin-top: 16px;
    }
    .auth-lang a {
        color: var(--text-muted);
        text-decoration: none;
        font-size: 0.85rem;
    }
    .auth-lang a:hover { color: var(--accent); }

    .form-group .btn {
        width: 100%;
        justify-content: center;
        padding: 14px;
        font-size: 1rem;
    }

    /* Scanline effect */
    .auth-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        background: linear-gradient(transparent 50%, rgba(0, 255, 136, 0.01) 50%);
        background-size: 100% 4px;
        pointer-events: none;
        border-radius: var(--radius-lg);
    }
</style>
@endsection

@section('content')
<div class="auth-page">
    <div class="auth-card animate-fade-up">
        <div class="auth-logo">
            <a href="{{ route('landing') }}">MONITORLY</a>
        </div>
        <h1 class="auth-title">{{ __('messages.login_title') }}</h1>
        <p class="auth-subtitle">{{ __('messages.login_subtitle') }}</p>

        @if($errors->any())
            <div class="alert alert-error">
                🔒 {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">{{ __('messages.email') }}</label>
                <input class="form-input" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="agent@monitorly.app">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">{{ __('messages.password') }}</label>
                <input class="form-input" type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember" style="color: var(--text-secondary); font-size: 0.9rem;">{{ __('messages.remember_me') }}</label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    🔐 {{ __('messages.login') }}
                </button>
            </div>
        </form>

        <div class="auth-footer">
            {{ __('messages.no_account') }}
            <a href="{{ route('register') }}">{{ __('messages.register') }}</a>
        </div>

        <div class="auth-lang">
            @if(app()->getLocale() === 'ar')
                <a href="{{ route('lang.switch', 'en') }}">English</a>
            @else
                <a href="{{ route('lang.switch', 'ar') }}">العربية</a>
            @endif
        </div>
    </div>
</div>
@endsection
