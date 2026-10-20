<x-guest-layout>

    <div class="mb-6 text-center">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider mb-2.5 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-800/60 shadow-xs">
            <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Akses Administrator
        </span>
        <h2 class="font-serif text-2xl sm:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">
            Masuk <span class="text-emerald-600 dark:text-emerald-400">Admin</span>
        </h2>
        <p class="text-xs sm:text-sm mt-1.5 text-slate-600 dark:text-slate-400 font-light">
            Portal tata kelola NU Kalisari Digital
        </p>
    </div>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-5"
          x-data="{ submitting: false, showPassword: false }"
          @submit="submitting = true">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">
                EMAIL
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   placeholder="nama@email.com"
                   class="w-full px-4 py-3 rounded-xl text-sm font-medium outline-none transition-all duration-200 bg-white dark:bg-[#0c140f] border border-slate-300 dark:border-white/10 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15 dark:focus:ring-emerald-500/20 shadow-xs">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">
                PASSWORD
            </label>
            <div class="relative">
                <input id="password" :type="showPassword ? 'text' : 'password'" name="password"
                       required autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full px-4 py-3 pr-12 rounded-xl text-sm font-medium outline-none transition-all duration-200 bg-white dark:bg-[#0c140f] border border-slate-300 dark:border-white/10 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15 dark:focus:ring-emerald-500/20 shadow-xs">
                
                {{-- Tombol lihat password pakai ikon mata --}}
                <button type="button" 
                        @click="showPassword = !showPassword" 
                        :aria-label="showPassword ? 'Sembunyikan password' : 'Lihat password'"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors cursor-pointer min-h-[44px]">
                    <!-- Eye icon (ketika belum ditampilkan) -->
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Eye off icon (ketika sedang ditampilkan) -->
                    <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        {{-- Remember + Forgot --}}
        <div class="flex items-center justify-between pt-1">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded border-slate-300 dark:border-white/10 text-emerald-600 focus:ring-emerald-500 w-4 h-4 cursor-pointer">
                <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Ingat saya</span>
            </label>
            @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}"
               class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline transition-colors">
                Lupa password?
            </a>
            @endif
        </div>

        {{-- Submit Button --}}
        <div class="pt-2">
            <button type="submit"
                    :disabled="submitting"
                    class="w-full py-3 px-5 min-h-[44px] rounded-xl text-white text-sm font-semibold transition-all duration-200 bg-emerald-600 hover:bg-emerald-700 hover:-translate-y-0.5 active:translate-y-0 hover:shadow-lg hover:shadow-emerald-950/20 active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                <template x-if="submitting">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                <span x-text="submitting ? 'Memproses Masuk...' : 'Masuk Admin'">Masuk Admin</span>
            </button>
        </div>
    </form>

</x-guest-layout>
