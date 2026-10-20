<x-guest-layout>

    <!-- Ikon Amplop di atas judul -->
    <div class="mb-5 text-center">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-3 border border-emerald-200/60 dark:border-emerald-800/40 shadow-xs">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        
        <!-- Judul: Lupa Password? (kata kedua hijau) -->
        <h2 class="font-serif text-2xl sm:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">
            Lupa <span class="text-emerald-600 dark:text-emerald-400">Password?</span>
        </h2>
        
        <!-- Teks Deskripsi Bahasa Indonesia -->
        <p class="text-xs sm:text-sm mt-1.5 text-slate-600 dark:text-slate-400 font-light leading-relaxed max-w-xs mx-auto">
            Masukkan alamat email yang terdaftar. Kami akan mengirimkan tautan untuk mengatur ulang password Anda.
        </p>
    </div>

    <!-- Status Sukses / Pesan Sesi -->
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4 sm:space-y-5"
          x-data="{ submitting: false }"
          @submit="submitting = true">
        @csrf

        <!-- Email Address -->
        <div>
            <!-- Gaya label uppercase kecil sama dengan login -->
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">
                EMAIL
            </label>
            <!-- Gaya input sama dengan login: border hijau + glow, kontras tinggi WCAG AA -->
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   placeholder="nama@email.com"
                   class="w-full px-4 py-3 rounded-xl text-sm font-medium outline-none transition-all duration-200 bg-white dark:bg-[#0c140f] border border-slate-300 dark:border-white/10 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15 dark:focus:ring-emerald-500/20 shadow-xs">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Tombol Hijau (bukan navy) dengan loading state & hover lift -->
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
                <span x-text="submitting ? 'Mengirim Tautan...' : 'Kirim Tautan Reset Password'">Kirim Tautan Reset Password</span>
            </button>
        </div>

        <!-- Link: Kembali ke halaman masuk -->
        <div class="pt-2 text-center">
            <a href="{{ route('login') }}" 
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline transition-colors min-h-[44px] py-2 px-3">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke halaman masuk</span>
            </a>
        </div>
    </form>

</x-guest-layout>
