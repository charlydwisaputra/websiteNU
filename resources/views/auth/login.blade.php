<x-guest-layout>

    <div class="mb-7 text-center">
        <span class="badge badge-green inline-flex mb-3">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Akses Administrator
        </span>
        <h2 class="font-serif text-2xl font-bold tracking-tight" style="color:var(--text-primary)">Masuk Admin</h2>
        <p class="text-sm mt-1" style="color:var(--text-muted)">Portal tata kelola NU Cikembulan Digital</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider mb-2"
                   style="color:var(--text-secondary)">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   placeholder="nama@email.com"
                   class="w-full px-4 py-3 rounded-xl text-sm font-medium outline-none transition-all"
                   style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                   onfocus="this.style.borderColor='var(--accent)';this.style.boxShadow='0 0 0 3px var(--accent-subtle)'"
                   onblur="this.style.borderColor='var(--border-medium)';this.style.boxShadow=''">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        {{-- Password --}}
        <div x-data="{ show: false }">
            <label for="password" class="block text-xs font-semibold uppercase tracking-wider mb-2"
                   style="color:var(--text-secondary)">Password</label>
            <div class="relative">
                <input id="password" :type="show ? 'text' : 'password'" name="password"
                       required autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full px-4 py-3 pr-16 rounded-xl text-sm font-medium outline-none transition-all"
                       style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                       onfocus="this.style.borderColor='var(--accent)';this.style.boxShadow='0 0 0 3px var(--accent-subtle)'"
                       onblur="this.style.borderColor='var(--border-medium)';this.style.boxShadow=''">
                <button type="button" @click="show=!show"
                        class="absolute inset-y-0 right-0 pr-4 text-xs font-semibold transition-colors"
                        style="color:var(--text-muted)"
                        onmouseover="this.style.color='var(--accent)'"
                        onmouseout="this.style.color='var(--text-muted)'"
                        x-text="show ? 'Tutup' : 'Lihat'"></button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        {{-- Remember + Forgot --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded border" style="border-color:var(--border-medium);color:var(--accent)">
                <span class="text-xs font-medium" style="color:var(--text-secondary)">Ingat saya</span>
            </label>
            @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}"
               class="text-xs font-semibold transition-colors"
               style="color:var(--accent)"
               onmouseover="this.style.color='var(--accent-hover)'"
               onmouseout="this.style.color='var(--accent)'">
                Lupa password?
            </a>
            @endif
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full py-3 px-5 rounded-full text-white text-sm font-semibold transition-all"
                style="background:var(--accent)"
                onmouseover="this.style.background='var(--accent-hover)';this.style.boxShadow='0 4px 16px rgba(0,138,90,0.25)'"
                onmouseout="this.style.background='var(--accent)';this.style.boxShadow=''">
            Masuk Admin
        </button>
    </form>

</x-guest-layout>
