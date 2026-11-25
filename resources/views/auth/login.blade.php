<x-guest-layout>
    <div class="auth-heading">
        <p class="auth-heading-label">Akses staf</p>
        <h2>Selamat datang kembali</h2>
        <p>Masuk untuk melanjutkan pengelolaan restoran Anda.</p>
    </div>

    <x-auth-session-status class="auth-status" :status="session('status')" />

    <form class="auth-form" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="auth-field">
            <label for="email">Alamat email</label>
            <div class="auth-input-wrap">
                <svg class="auth-input-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="3.5" y="5" width="17" height="14" rx="2.5" stroke="currentColor" stroke-width="1.6"/>
                    <path d="m5 7 7 5.5L19 7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <input
                    id="email"
                    class="auth-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="nama@restoran.com"
                    required
                    autofocus
                    autocomplete="username"
                    aria-describedby="email-error"
                >
            </div>
            @error('email')
                <p class="auth-error" id="email-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth-field">
            <label for="password">Kata sandi</label>
            <div class="auth-input-wrap">
                <svg class="auth-input-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="4.5" y="10" width="15" height="11" rx="2.5" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M8 10V7a4 4 0 1 1 8 0v3M12 14v3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
                <input
                    id="password"
                    class="auth-input {{ $errors->has('email') || $errors->has('password') ? 'is-invalid' : '' }}"
                    type="password"
                    name="password"
                    placeholder="Masukkan kata sandi"
                    required
                    autocomplete="current-password"
                    aria-describedby="password-error"
                >
            </div>
            @error('password')
                <p class="auth-error" id="password-error">{{ $message }}</p>
            @enderror
        </div>

        <button class="auth-submit" type="submit">
            Masuk ke dashboard
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true">
                <path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </form>
</x-guest-layout>
