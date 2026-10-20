@props(['status'])

@if ($status)
    @php
        $statusMsg = $status;
        if ($status === 'passwords.sent' || $status === __('passwords.sent') || str_contains($status, 'emailed your password reset link')) {
            $statusMsg = 'Tautan reset password telah berhasil kami kirimkan ke email Anda. Silakan periksa kotak masuk atau spam.';
        } elseif ($status === 'passwords.reset' || $status === __('passwords.reset') || str_contains($status, 'password has been reset')) {
            $statusMsg = 'Password Anda berhasil diperbarui. Silakan masuk menggunakan password baru.';
        }
    @endphp
    <div {{ $attributes->merge(['class' => 'p-3.5 sm:p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs sm:text-sm font-medium text-emerald-800 dark:text-emerald-300 flex items-start gap-2.5 shadow-xs']) }}>
        <svg class="w-4 h-4 shrink-0 mt-0.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="leading-relaxed">{{ $statusMsg }}</span>
    </div>
@endif
