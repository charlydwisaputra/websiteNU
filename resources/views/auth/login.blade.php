<x-guest-layout>
    <div class="mb-6 text-center">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-[#087A4B] border border-emerald-200/60 text-[11px] font-bold mb-3 shadow-2xs">
            <svg class="w-3 h-3 text-[#087A4B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span>Akses Masuk Administrator</span>
        </span>
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Masuk Admin</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Portal tata kelola & administrator DIGDAYA NU</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block font-bold text-xs uppercase tracking-wider text-slate-600 mb-1.5">Email</label>
            <input id="email"
                   class="block w-full border border-slate-200/80 bg-slate-50/60 focus:bg-white focus:border-[#087A4B] focus:ring-2 focus:ring-emerald-500/15 rounded-2xl px-4 py-2.5 text-sm transition shadow-2xs outline-none"
                   type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                   placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <label for="password" class="block font-bold text-xs uppercase tracking-wider text-slate-600 mb-1.5">Password</label>
            <div class="relative">
                <input id="password"
                       class="block w-full border border-slate-200/80 bg-slate-50/60 focus:bg-white focus:border-[#087A4B] focus:ring-2 focus:ring-emerald-500/15 rounded-2xl px-4 py-2.5 text-sm transition shadow-2xs outline-none pr-14"
                       :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                       placeholder="••••••••" />
                <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-xs font-semibold text-slate-400 hover:text-[#087A4B] transition">
                    <span x-text="show ? 'Tutup' : 'Lihat'"></span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox"
                       class="rounded-lg border-slate-300 text-[#087A4B] shadow-2xs focus:ring-[#087A4B]" name="remember">
                <span class="ms-2 text-xs font-medium text-slate-600">Ingat Saya</span>
            </label>

            @if (\Illuminate\Support\Facades\Route::has('password.request'))
                <a class="text-xs font-semibold text-[#087A4B] hover:underline" href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif
        </div>

        <!-- Buttons -->
        <div class="pt-2 flex flex-col gap-3">
            <button type="submit"
                    class="w-full py-2.5 px-5 bg-[#087A4B] hover:bg-[#065C39] active:scale-95 text-white font-bold text-xs sm:text-sm rounded-full shadow-md shadow-emerald-900/15 hover:shadow-lg hover:shadow-emerald-900/20 transition-all duration-200 flex items-center justify-center">
                Masuk Admin
            </button>
        </div>
    </form>
</x-guest-layout>