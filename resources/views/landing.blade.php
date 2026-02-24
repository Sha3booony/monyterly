@extends('layouts.app')

@section('title', 'Monitorly — Website Monitoring Platform')

@section('styles')
<style>
    /* ═══════════════════════════════════════════
       NAVIGATION
    ═══════════════════════════════════════════ */
    .nav {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 100;
        padding: 16px 0;
        transition: all 0.3s ease;
        background: transparent;
    }
    .nav.scrolled {
        background: rgba(10, 10, 15, 0.9);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border);
    }
    .nav-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
    }
    .nav-logo {
        font-family: var(--font-display);
        font-size: 1.4rem;
        font-weight: 800;
        text-decoration: none;
        background: var(--gradient-1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: 2px;
    }
    .nav-links {
        display: flex;
        align-items: center;
        gap: 24px;
        list-style: none;
    }
    .nav-links a {
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: color 0.3s;
    }
    .nav-links a:hover { color: var(--accent); }
    .nav-lang {
        padding: 6px 14px;
        border-radius: 20px;
        border: 1px solid var(--border);
        color: var(--text-secondary);
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s;
    }
    .nav-lang:hover {
        border-color: var(--accent);
        color: var(--accent);
    }

    /* ═══════════════════════════════════════════
       HERO SECTION
    ═══════════════════════════════════════════ */
    .hero {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: 100px 24px 60px;
    }

    /* Matrix rain background */
    .hero-bg {
        position: absolute;
        inset: 0;
        overflow: hidden;
        opacity: 0.06;
    }
    .hero-bg .matrix-col {
        position: absolute;
        top: -100%;
        font-family: var(--font-mono);
        font-size: 14px;
        color: var(--accent);
        writing-mode: vertical-rl;
        animation: matrixFall linear infinite;
        white-space: nowrap;
    }
    @keyframes matrixFall {
        to { transform: translateY(300vh); }
    }

    /* Grid overlay */
    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(0, 255, 136, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 255, 136, 0.03) 1px, transparent 1px);
        background-size: 60px 60px;
        z-index: 1;
    }

    /* Radial light */
    .hero::after {
        content: '';
        position: absolute;
        top: -40%;
        left: 50%;
        transform: translateX(-50%);
        width: 900px;
        height: 900px;
        background: radial-gradient(circle, rgba(0, 255, 136, 0.08) 0%, transparent 70%);
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 10;
        text-align: center;
        max-width: 800px;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        border-radius: 30px;
        background: rgba(0, 255, 136, 0.08);
        border: 1px solid rgba(0, 255, 136, 0.2);
        color: var(--accent);
        font-family: var(--font-mono);
        font-size: 0.85rem;
        margin-bottom: 30px;
        animation: glow 3s infinite;
    }
    .hero-badge::before {
        content: '●';
        animation: pulse-green 1.5s infinite;
    }

    .hero h1 {
        font-family: var(--font-display);
        font-size: clamp(2.2rem, 5vw, 3.8rem);
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: 24px;
        letter-spacing: 1px;
    }
    .hero h1 .accent-text {
        background: var(--gradient-1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero p {
        font-size: 1.15rem;
        color: var(--text-secondary);
        max-width: 600px;
        margin: 0 auto 40px;
        line-height: 1.8;
    }

    .hero-actions {
        display: flex;
        gap: 16px;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* Terminal widget */
    .hero-terminal {
        margin-top: 60px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 0;
        text-align: start;
        max-width: 650px;
        margin-left: auto;
        margin-right: auto;
        overflow: hidden;
        box-shadow: var(--shadow), 0 0 60px rgba(0, 255, 136, 0.05);
    }
    .terminal-header {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 18px;
        background: var(--bg-secondary);
        border-bottom: 1px solid var(--border);
    }
    .terminal-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    .terminal-dot.red { background: #ff5f57; }
    .terminal-dot.yellow { background: #ffbd2e; }
    .terminal-dot.green { background: #28c840; }
    .terminal-title {
        flex: 1;
        text-align: center;
        font-family: var(--font-mono);
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    .terminal-body {
        padding: 20px;
        font-family: var(--font-mono);
        font-size: 0.85rem;
        line-height: 2;
    }
    .terminal-line { color: var(--text-secondary); }
    .terminal-line .cmd { color: var(--accent); }
    .terminal-line .url { color: var(--info); }
    .terminal-line .ok { color: #00ff88; }
    .terminal-line .err { color: #ff3366; }
    .terminal-line .dim { color: var(--text-muted); }
    .terminal-cursor {
        display: inline-block;
        width: 8px;
        height: 16px;
        background: var(--accent);
        animation: blink 1s step-end infinite;
        vertical-align: text-bottom;
    }
    @keyframes blink {
        50% { opacity: 0; }
    }

    /* ═══════════════════════════════════════════
       FEATURES SECTION
    ═══════════════════════════════════════════ */
    .features {
        padding: 120px 0;
        position: relative;
    }
    .features::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--accent), transparent);
        opacity: 0.3;
    }

    .section-label {
        font-family: var(--font-mono);
        font-size: 0.85rem;
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 12px;
    }
    .section-title {
        font-family: var(--font-display);
        font-size: clamp(1.8rem, 3vw, 2.8rem);
        font-weight: 700;
        margin-bottom: 60px;
        letter-spacing: 1px;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 24px;
    }

    .feature-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 32px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient-1);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }
    .feature-card:hover {
        border-color: var(--accent);
        transform: translateY(-4px);
        box-shadow: var(--shadow-glow);
    }
    .feature-card:hover::before { transform: scaleX(1); }

    .feature-icon {
        width: 56px;
        height: 56px;
        border-radius: var(--radius);
        background: rgba(0, 255, 136, 0.08);
        border: 1px solid rgba(0, 255, 136, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }

    .feature-card h3 {
        font-family: var(--font-display);
        font-size: 1.05rem;
        font-weight: 600;
        margin-bottom: 12px;
        letter-spacing: 0.5px;
    }
    .feature-card p {
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.7;
    }

    /* ═══════════════════════════════════════════
       STATS SECTION
    ═══════════════════════════════════════════ */
    .stats {
        padding: 80px 0;
        background: var(--bg-secondary);
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 40px;
        text-align: center;
    }
    .stat-number {
        font-family: var(--font-display);
        font-size: 2.5rem;
        font-weight: 800;
        background: var(--gradient-1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .stat-label {
        color: var(--text-secondary);
        font-size: 0.95rem;
        margin-top: 8px;
    }

    /* ═══════════════════════════════════════════
       CTA SECTION
    ═══════════════════════════════════════════ */
    .cta {
        padding: 120px 0;
        text-align: center;
        position: relative;
    }
    .cta::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(0, 255, 136, 0.06) 0%, transparent 70%);
    }
    .cta-content {
        position: relative;
        z-index: 10;
    }
    .cta h2 {
        font-family: var(--font-display);
        font-size: clamp(1.6rem, 3vw, 2.5rem);
        font-weight: 700;
        margin-bottom: 16px;
        letter-spacing: 1px;
    }
    .cta p {
        color: var(--text-secondary);
        font-size: 1.1rem;
        margin-bottom: 40px;
    }

    /* ═══════════════════════════════════════════
       FOOTER
    ═══════════════════════════════════════════ */
    .footer {
        padding: 40px 0;
        border-top: 1px solid var(--border);
        text-align: center;
    }
    .footer p {
        color: var(--text-muted);
        font-size: 0.9rem;
    }
    .footer-logo {
        font-family: var(--font-display);
        font-size: 1.1rem;
        font-weight: 700;
        background: var(--gradient-1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 12px;
        letter-spacing: 2px;
    }

    /* Mobile */
    @media (max-width: 768px) {
        .nav-links { gap: 16px; }
        .hero { padding: 90px 16px 40px; }
        .hero-terminal { display: none; }
        .features-grid { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 24px; }
    }
</style>
@endsection

@section('content')
<!-- Navigation -->
<nav class="nav" id="nav">
    <div class="nav-inner">
        <a href="{{ route('landing') }}" class="nav-logo">MONITORLY</a>
        <ul class="nav-links">
            <li><a href="#features">{{ __('messages.features') }}</a></li>
            @auth
                <li><a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">{{ __('messages.dashboard') }}</a></li>
            @else
                <li><a href="{{ route('login') }}">{{ __('messages.login') }}</a></li>
                <li><a href="{{ route('register') }}" class="btn btn-primary btn-sm">{{ __('messages.register') }}</a></li>
            @endauth
            <li>
                @if(app()->getLocale() === 'ar')
                    <a href="{{ route('lang.switch', 'en') }}" class="nav-lang">English</a>
                @else
                    <a href="{{ route('lang.switch', 'ar') }}" class="nav-lang">العربية</a>
                @endif
            </li>
        </ul>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-bg" id="matrixBg"></div>
    <div class="hero-content">
        <div class="hero-badge animate-fade-up">SYSTEM.ACTIVE // MONITORING.ONLINE</div>

        <h1 class="animate-fade-up delay-1">
            {{ app()->getLocale() === 'ar' ? 'راقب مواقعك' : 'Monitor Your' }}<br>
            <span class="accent-text">{{ app()->getLocale() === 'ar' ? 'باحترافية' : 'Websites Like a Pro' }}</span>
        </h1>

        <p class="animate-fade-up delay-2">{{ __('messages.hero_subtitle') }}</p>

        <div class="hero-actions animate-fade-up delay-3">
            <a href="{{ route('register') }}" class="btn btn-primary">
                ⚡ {{ __('messages.get_started') }}
            </a>
            <a href="#features" class="btn btn-secondary">
                {{ __('messages.learn_more') }} →
            </a>
        </div>

        <!-- Terminal Widget -->
        <div class="hero-terminal animate-fade-up delay-4">
            <div class="terminal-header">
                <span class="terminal-dot red"></span>
                <span class="terminal-dot yellow"></span>
                <span class="terminal-dot green"></span>
                <span class="terminal-title">monitorly@agent:~</span>
            </div>
            <div class="terminal-body">
                <div class="terminal-line"><span class="cmd">$</span> monitorly check <span class="url">https://yoursite.com</span></div>
                <div class="terminal-line"><span class="dim">[14:23:01]</span> <span class="ok">✓ 200 OK</span> — Response: <span class="ok">142ms</span></div>
                <div class="terminal-line"><span class="dim">[14:28:01]</span> <span class="ok">✓ 200 OK</span> — Response: <span class="ok">156ms</span></div>
                <div class="terminal-line"><span class="dim">[14:33:01]</span> <span class="err">✗ TIMEOUT</span> — <span class="err">Site is DOWN!</span></div>
                <div class="terminal-line"><span class="dim">[14:33:02]</span> <span class="cmd">→</span> Alert sent to <span class="url">user@email.com</span></div>
                <div class="terminal-line"><span class="dim">[14:33:02]</span> <span class="cmd">→</span> Issue <span class="ok">#1042</span> created</div>
                <div class="terminal-line"><span class="cmd">$</span> <span class="terminal-cursor"></span></div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features" id="features">
    <div class="container text-center">
        <div class="section-label animate-fade-up">// {{ __('messages.features') }}</div>
        <h2 class="section-title animate-fade-up delay-1">
            {{ app()->getLocale() === 'ar' ? 'كل ما تحتاجه للمراقبة' : 'Everything You Need to Monitor' }}
        </h2>

        <div class="features-grid">
            <div class="feature-card animate-fade-up delay-1">
                <div class="feature-icon">📡</div>
                <h3>{{ __('messages.feature_monitoring') }}</h3>
                <p>{{ __('messages.feature_monitoring_desc') }}</p>
            </div>
            <div class="feature-card animate-fade-up delay-2">
                <div class="feature-icon">🔔</div>
                <h3>{{ __('messages.feature_alerts') }}</h3>
                <p>{{ __('messages.feature_alerts_desc') }}</p>
            </div>
            <div class="feature-card animate-fade-up delay-3">
                <div class="feature-icon">🎫</div>
                <h3>{{ __('messages.feature_tickets') }}</h3>
                <p>{{ __('messages.feature_tickets_desc') }}</p>
            </div>
            <div class="feature-card animate-fade-up delay-4">
                <div class="feature-icon">⏱️</div>
                <h3>{{ __('messages.feature_interval') }}</h3>
                <p>{{ __('messages.feature_interval_desc') }}</p>
            </div>
            <div class="feature-card animate-fade-up delay-5">
                <div class="feature-icon">🌐</div>
                <h3>{{ __('messages.feature_bilingual') }}</h3>
                <p>{{ __('messages.feature_bilingual_desc') }}</p>
            </div>
            <div class="feature-card animate-fade-up delay-6">
                <div class="feature-icon">📊</div>
                <h3>{{ __('messages.feature_uptime') }}</h3>
                <p>{{ __('messages.feature_uptime_desc') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats">
    <div class="container">
        <div class="stats-grid">
            <div class="animate-fade-up">
                <div class="stat-number">99.9%</div>
                <div class="stat-label">{{ app()->getLocale() === 'ar' ? 'دقة المراقبة' : 'Monitoring Accuracy' }}</div>
            </div>
            <div class="animate-fade-up delay-1">
                <div class="stat-number">&lt;1s</div>
                <div class="stat-label">{{ app()->getLocale() === 'ar' ? 'وقت التنبيه' : 'Alert Speed' }}</div>
            </div>
            <div class="animate-fade-up delay-2">
                <div class="stat-number">24/7</div>
                <div class="stat-label">{{ app()->getLocale() === 'ar' ? 'مراقبة مستمرة' : 'Non-Stop Monitoring' }}</div>
            </div>
            <div class="animate-fade-up delay-3">
                <div class="stat-number">∞</div>
                <div class="stat-label">{{ app()->getLocale() === 'ar' ? 'مواقع بلا حدود' : 'Unlimited Sites' }}</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta">
    <div class="container cta-content">
        <h2 class="animate-fade-up">{{ __('messages.cta_title') }}</h2>
        <p class="animate-fade-up delay-1">{{ __('messages.cta_subtitle') }}</p>
        <a href="{{ route('register') }}" class="btn btn-primary animate-fade-up delay-2">
            🚀 {{ __('messages.get_started') }}
        </a>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-logo">MONITORLY</div>
        <p>{{ __('messages.footer_text') }}</p>
    </div>
</footer>
@endsection

@section('scripts')
<script>
// Navbar scroll effect
window.addEventListener('scroll', () => {
    const nav = document.getElementById('nav');
    if (window.scrollY > 50) nav.classList.add('scrolled');
    else nav.classList.remove('scrolled');
});

// Matrix rain background
function createMatrixRain() {
    const bg = document.getElementById('matrixBg');
    if (!bg) return;
    const chars = '01アイウエオカキクケコサシスセソタチツテトナニヌネノ';
    const colCount = Math.floor(window.innerWidth / 30);

    for (let i = 0; i < colCount; i++) {
        const col = document.createElement('div');
        col.className = 'matrix-col';
        col.style.left = `${(i / colCount) * 100}%`;
        col.style.animationDuration = `${8 + Math.random() * 12}s`;
        col.style.animationDelay = `${Math.random() * 5}s`;

        let text = '';
        for (let j = 0; j < 30; j++) {
            text += chars[Math.floor(Math.random() * chars.length)] + '\n';
        }
        col.textContent = text;
        bg.appendChild(col);
    }
}

// Intersection observer for animations
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animationPlayState = 'running';
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.animate-fade-up').forEach(el => {
    el.style.animationPlayState = 'paused';
    observer.observe(el);
});

createMatrixRain();
</script>
@endsection
